<?php


namespace Webbinaro\AdvCalendar\Api\Controllers;

use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\User\Exception\PermissionDeniedException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Flarum\User\AssertPermissionTrait;
use Webbinaro\AdvCalendar\Event as AdvEvent;

class EventsDeleteController extends AbstractDeleteController
{
    use AssertPermissionTrait;
    protected function delete(Request $request)
    {
        $id = array_get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);
        $event = AdvEvent::findOrFail($id);
        if(! $actor->can('event.moderate') && $actor->id !== $event->user->id ) {
            throw new PermissionDeniedException("non moderator unowned event");
        }
        $event->delete();
    }
}
