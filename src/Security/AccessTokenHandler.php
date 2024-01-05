<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;

class AccessTokenHandler implements AccessTokenHandlerInterface
{

    public function __construct(private \Redis $redis)
    {
    }

    /**
     * @param User $user User object
     *
     * @return string Access Token
     */
    public function createForUser(User $user): string
    {
        $accessToken = session_create_id();
        $this->redis->setEx('sessions/' . $accessToken, 3 * 60 * 60, $user->getUserIdentifier());

        return $accessToken;
    }

    /**
     * @param string $accessToken Access Token
     *
     * @throws AuthenticationException
     */
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $userId = $this->redis->get('sessions/' . $accessToken);

        if (!$userId)
        {
            throw new BadCredentialsException('Invalid access token');
        }

        return new UserBadge($userId);
    }

}
?>
