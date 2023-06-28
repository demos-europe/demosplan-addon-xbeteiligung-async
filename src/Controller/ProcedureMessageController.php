<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Controller;

use DemosEurope\DemosplanAddon\Controller\APIController;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProcedureMessageController extends APIController
{

    /**
     * Triggers a fetch for all given ProcedureMessage-urls to retrieve last update from Procedure
     * and saves them as new ProcedureMessage.
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
    #[Route('/api/procedure/{authToken}/{id}/', name: 'dplan_api_procedure_messages_insert', methods: ['GET'])]
    public function importNewImportableProcedureMessage(ProcedureMessageRepository $procedureMessageRepository,string $authToken, string $id)
    {
        if ($authToken !== $this->getParameter('xbeteiligung_api_token')) {
            return new Response(null, Response::HTTP_NO_CONTENT, []);
        }

        // we have to check if a corresponding procedure exists and get it if so
        try {
            $procedureMessageRepository->getProcedureMessage($id);
        } catch (NoResultException|NonUniqueResultException $e) {
            $this->logger->warning('No unique procedure message found for given ID', [
                'exception' => $e->getMessage()
            ]);
        }

        return new Response(null, Response::HTTP_NO_CONTENT, []);
    }

}