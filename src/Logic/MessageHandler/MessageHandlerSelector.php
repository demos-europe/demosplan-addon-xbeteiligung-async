<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler;

use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\IncomingMessageHandlerInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\GenericProcedureMessageHandler;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\GenericStatementResponseHandler;
use InvalidArgumentException;

/**
 * Selects the appropriate message handler based on message type.
 */
class MessageHandlerSelector
{
    private array $handlers;

    public function __construct(
        GenericProcedureMessageHandler $procedureMessageHandler,
        GenericStatementResponseHandler $statementResponseHandler
    ) {
        $this->handlers = [
            GenericProcedureMessageHandler::class => $procedureMessageHandler,
            GenericStatementResponseHandler::class => $statementResponseHandler,
        ];
    }

    /**
     * Get the appropriate handler for a given message type.
     *
     * @param string $messageType The XBeteiligung message type string
     * @return IncomingMessageHandlerInterface The handler capable of processing this message type
     * @throws InvalidArgumentException If no handler exists for the message type
     */
    public function getHandlerForMessageType(string $messageType): IncomingMessageHandlerInterface
    {
        $messageEnum = XBeteiligungMessageType::tryFrom($messageType);

        if (null === $messageEnum) {
            throw new InvalidArgumentException("Unsupported message type: {$messageType}");
        }

        $handlerClass = $messageEnum->getHandlerClass();

        return $this->handlers[$handlerClass] ?? throw new InvalidArgumentException(
            "No handler instance found for class: {$handlerClass}"
        );
    }
}
