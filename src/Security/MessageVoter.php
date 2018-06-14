<?php
namespace App\Security;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

// Grant or deny permissions for actions related to messages (edit/delete)

class MessageVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';


    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::EDIT, self::DELETE))) {
            return false;
        }

        // only applicable on Message objects inside this voter
        if (!$subject instanceof Message) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Message object, thanks to supports
        /** @var Message $message */
        $message = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($message, $user);
            case self::DELETE:
                return $this->canDelete($message, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Message $message, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($message, $user)) {
            return true;
        }

        // the Message object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return !$message->isPrivate();
    }

    private function canEdit(Message $message, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $message->getOwner();
    }
}