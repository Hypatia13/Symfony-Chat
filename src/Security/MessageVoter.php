<?php
namespace App\Security;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

// Grant or deny permissions for actions related to messages (edit/delete)

class MessageVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't supported, return false
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
            // deny access, if not logged in
            return false;
        }

        // $subject is a Message object
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

    private function canEdit(Message $message, User $user)
    {
        // if they can delete, they can edit
        if ($this->canDelete($message, $user)) {
            return true;
        }
    }

    private function canDelete(Message $message, User $user)
    {
        // Checks whether the user has Admin or Moderator rights
    if ($this->decisionManager->decide($token, array('ROLE_ADMIN')) || $this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }
    }
}