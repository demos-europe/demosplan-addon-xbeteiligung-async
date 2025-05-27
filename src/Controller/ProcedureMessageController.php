<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Controller;

use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use DemosEurope\DemosplanAddon\Controller\APIController;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProcedureMessageController extends APIController
{
    #[Route(
        path: '/api/procedure_message/{procedureMessageId}',
        name: 'dplan_api_procedure_message_show',
        options: ['expose' => true],
        methods: ['GET']
    )]
    public function showNewImportableProcedureMessage(
        ProcedureMessageRepository $procedureMessageRepository,
        Request $request,
        string $procedureMessageId
    ): Response {
        if ($this->hasNoValidAuthToken($request->headers->get('authToken'))) {
            return $this->renderEmpty();
        }

        try {
            $message = $procedureMessageRepository->getProcedureMessage($procedureMessageId);
            $procedureMessageRepository->updateObject($procedureMessageId);
            $response = new Response($message, 200, ['Content-Type' => 'application/xml']);
        } catch (NoResultException|NonUniqueResultException $e) {
            $this->logger->warning('No unique procedure message found for given ID.', [
                'exception' => $e->getMessage()
            ]);
            $response = $this->handleApiError($e);
        } catch (Exception $e) {
            $this->logger->warning('An exception occurred during procedure message processing for given ID.', [
                'exception' => $e->getMessage()
            ]);
            $response = $this->handleApiError($e);
        }

        return $response;
    }

    /**
     * Triggers a fetch for all given ProcedureMessage-urls to retrieve last update from Procedure
     * and saves them as new ProcedureMessage.
     *
     *
     * **PLEASE NOTE**: We technically want feature_import_ProcedureMessage as access
     * permission here. Due to current time constraints, this is not possible as we
     * do not want to give the guest user that permission. Authenticating from xbeteiligung
     * with any other user would require implementing JWT support which will happen
     * in the near future. Until then, access is limited with a purpose-generated
     * token stored in `addon_xbeteiligung_async_api_token`.
     */
    #[Route(path: 'api/new/procedure_message/ids', name: 'dplan_api_get_new_procedure_messages_ids', methods: ['GET'])]
    public function showNewImportableProcedureMessages(
        ProcedureMessageRepository $procedureMessageRepository,
        ProcedureServiceInterface $procedureService,
        Request $request
    ): Response {
        if ($this->hasNoValidAuthToken($request->headers->get('authToken'))) {
            return $this->renderEmpty();
        }
        if ($request->headers->get('authToken') !== $this->getParameter('addon_xbeteiligung_async_api_token')) {
            return $this->createEmptyResponse();
        }

        $procedureMessages = $procedureMessageRepository->findBy(['requestCount' => 0]);
        $responseData = [];

        /** @var ProcedureMessage $procedureMessage */
        foreach ($procedureMessages as $procedureMessage) {
            $procedureId = $procedureMessage->getProcedureId();
            $procedure = $procedureService->getProcedure($procedureId);
            $origin = '' === $procedure->getXtaPlanId() ?
                'DiPlanBeteiligung' : 'DiPlanCockpit'
            ;
            $subdomain = null;

            // Get the customer subdomain if procedure is available
            if (null !== $procedure?->getCustomer()) {
                $subdomain = $procedure->getCustomer()->getSubdomain();
            }

            $responseData[] = [
                'type' => 'procedure_message',
                'id' => $procedureMessage->getId(),
                'attributes' => [
                    'customer' => $subdomain,
                    'origin' => $origin,
                ]
            ];
        }

        return $this->createResponse(['data' => $responseData], 200);
    }

    /**
     * @throws Exception
     */
    #[Route(
        path: 'api/procedure_message/delete/{procedureMessageId}',
        name: 'dplan_api_procedure_message_delete',
        methods: ['GET']
    )]
    public function markProcedureMessageAsDeleted(
        ProcedureMessageRepository $procedureMessageRepository,
        Request $request,
        string $procedureMessageId
    ): Response {
        if ($this->hasNoValidAuthToken($request->headers->get('authToken'))) {
            return $this->renderEmpty();
        }

        $procedureMessageToMarkAsDeleted = $procedureMessageRepository->get($procedureMessageId);
        $procedureMessageToMarkAsDeleted->setDeleted();
        $procedureMessageRepository->updateObject($procedureMessageToMarkAsDeleted->getId());

        return  $this->createResponse([true], 200);
    }

    #[Route(
        path: 'api/procedure_message/error/{procedureMessageId}',
        name: 'dplan_api_procedure_message_error',
        methods: ['GET']
    )]
    public function markProcedureMessageAsError(
        ProcedureMessageRepository $procedureMessageRepository,
        Request $request,
        string $procedureMessageId
    ): Response {
        if ($this->hasNoValidAuthToken($request->headers->get('authToken'))) {
            return $this->renderEmpty();
        }

        $procedureMessageToMarkAsDeleted = $procedureMessageRepository->get($procedureMessageId);
        $procedureMessageToMarkAsDeleted->setError(true);
        $procedureMessageRepository->updateObject($procedureMessageToMarkAsDeleted->getId());

        return  $this->createResponse([true], 200);
    }

    private function hasNoValidAuthToken(string|null $authToken): bool
    {
        return $authToken !== $this->getParameter('addon_xbeteiligung_async_api_token');
    }
}
