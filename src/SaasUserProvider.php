<?php

namespace Aegeansea\KfSaas;

use App\Caches\UserCache;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class SaasUserProvider implements UserProvider
{
    /**
     * The login users.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->getGenericUser(json_decode(UserCache::init($identifier)->get()));
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->getGenericUser(json_decode(UserCache::init($identifier)->get()));
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        //
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        /*
         * 201：验证通过
         * 403：连续三次登录错误，请在3分钟后重试
         * 404：账号错误
         * 406：密码错误
         * 407：令牌错误
         */
        $result = (new AccountLogin())->login($credentials['email'], $credentials['password']);
        if (!empty($result['server_response']) && $result['server_response']['status_code'] != 201) {
            return $this->getGenericUser($result['server_response']);
        } else {
            $id6d = $result['server_response']['id6d'];
            $company_id = $result['server_response']['company_id'];
            $worker = (new Worker())->worker($id6d, $company_id);
            $worker['server_response']['id'] = $id6d . '.' . $company_id;
            $worker['server_response']['company'] = $result['server_response'];
            return $this->getGenericUser($worker['server_response']);
        }
    }

    /**
     * Get the generic user.
     *
     * @param  mixed $user
     * @return \Aegeansea\KfSaas\GenericUser|null
     */
    protected function getGenericUser($user)
    {
        if (!is_null($user)) {
            return new GenericUser((array)$user);
        }
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $this->user = $user;
        if (isset($user->status_code) && $user->status_code == 201) {
            $identifier = $user->id;
            UserCache::init($identifier)->set(json_encode($user->toArray()));
            return true;
        }

        return false;
    }
}
