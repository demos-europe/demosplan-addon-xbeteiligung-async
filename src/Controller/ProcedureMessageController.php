<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Controller;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\MessageBagInterface;
use DemosEurope\DemosplanAddon\Controller\APIController;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use EDT\JsonApi\Validation\FieldsValidator;
use EDT\Wrapping\TypeProviders\PrefilledTypeProvider;
use EDT\Wrapping\Utilities\SchemaPathProcessor;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

class ProcedureMessageController extends APIController
{
    public function __construct(
        LoggerInterface $apiLogger,
        FieldsValidator $fieldsValidator,
        PrefilledTypeProvider $resourceTypeProvider,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        GlobalConfigInterface $globalConfig,
        MessageBagInterface $messageBag,
        SchemaPathProcessor $schemaPathProcessor,
        ProcedureMessageRepository $ProcedureMessageRepository
    ) {
        parent::__construct(
            $apiLogger,
            $resourceTypeProvider,
            $fieldsValidator,
            $translator,
            $logger,
            $globalConfig,
            $messageBag,
            $schemaPathProcessor
        );
        $this->ProcedureMessageRepository = $ProcedureMessageRepository;
    }

    /**
     * Triggers a fetch for all given ProcedureMessage-urls to retrieve last update from Procedure
     * and saves them as new ProcedureMessage.
     *
     * @Route(
     *        path="/api/plugins/x-beteiligung/{ProcedureMessage}",
     *        methods={"POST"},
     *        name="dplan_api_procedure_messages_insert"
     *     )
     *
     * **PLEASE NOTE**: We technically want feature_import_ProcedureMessage as access
     * permission here. Due to current time constraints, this is not possible as we
     * do not want to give the guest user that permission. Authenticating from xbeteiligung
     * with any other user would require implementing JWT support which will happen
     * in the near future. Until then, access is limited with a purpose-generated
     * token stored in `xbeteiligung_api_token`.
     *
     * @return ProcedureMessage|Response
     */

    public function importNewImportableProcedureMessage(string $authToken, string $id)
    {
        if ($authToken !== $this->getParameter('xbeteiligung_api_token')) {
            return new Response(null, Response::HTTP_NO_CONTENT, []);
        }

        // we have to check if a corresponding procedure exists and get it if so
        try {
            $this->ProcedureMessageRepository->getProcedureMessage($id);
        } catch (NoResultException|NonUniqueResultException $e) {
            $this->logger->warning('No unique procedure message found for given ID', [
                'exception' => $e->getMessage()
            ]);
        }

        return new Response(null, Response::HTTP_NO_CONTENT, []);
    }

}