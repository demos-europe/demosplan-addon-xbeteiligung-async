<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Controller;

use DemosEurope\DemosplanAddon\Controller\APIController;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProcedureMessageController extends APIController
{
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
     * token stored in `xbeteiligung_api_token`.
     */
    #[Route(
        path: '/api/procedure_message/{procedureMessageId}',
        name: 'dplan_api_procedure_messages_show',
        options: ['expose' => true],
        methods: ['GET']
    )]
    public function showNewImportableProcedureMessage(
        ProcedureMessageRepository $procedureMessageRepository,
        Request $request,
        string $procedureMessageId
    ): Response {
        if ($request->headers->get('authToken') !== $this->getParameter('xbeteiligung_api_token')) {
            return $this->createEmptyResponse();
        }

        try {
            $message = $procedureMessageRepository->getProcedureMessage($procedureMessageId);
            $procedureMessageRepository->updateObject($procedureMessageId);
            $response = $this->createResponse([$message], 200);
        } catch (NoResultException|NonUniqueResultException $e) {
            $this->logger->warning('No unique procedure message found for given ID', [
                'exception' => $e->getMessage()
            ]);
            $response = $this->handleApiError($e);
        }

        return $response;
    }
}
