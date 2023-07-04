<?php

namespace App\Services\Base;

use App\Enums\StatusCode;
use Exception;
use App\Exceptions\ErrException;
use App\Models\SocialAccount;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SocialLoginService
{
    const FACEBOOK = 'facebook';
    const GOOGLE = 'google';
    const LINE = 'line';

    /**
     * @throws Exception|GuzzleException
     */
    public function getFacebookAuth($accessToken): array
    {
        $endpoint = 'https://graph.facebook.com/me';
        $params = [
            'access_token' => $accessToken,
            'fields' => 'id,name,email,picture',
        ];

        try {
            $client = new Client();
            $response = $client->get($endpoint, ['query' => $params]);
            $userData = json_decode($response->getBody(), true);

            return [
                'id' => $userData['id'] ?? null,
                'name' => $userData['name'] ?? null,
                'email' => $userData['email'] ?? null,
                'picture' => $userData['picture'] ?? null,
            ];
        } catch (RequestException $e) {
            $error_description = $e->getMessage();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $content = json_decode($response->getBody()->getContents(), true);

                if (Arr::has($content, 'error')) {
                    $error_description = $content['error']['message'];
                } else {
                    $error_description = "API returned error with status code: $statusCode";
                }
            }

            throw new ErrException($error_description);
        } catch (\Exception $e) {
            throw new ErrException($e->getMessage());
        }
    }

    public function getLineAuth($accessToken)
    {
        $endpoint = 'https://api.line.me/v2/profile';
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Accept' => 'application/json',
        ];

        try {
            $client = new Client();
            $response = $client->get($endpoint, ['headers' => $headers]);
            $userData = json_decode($response->getBody(), true);

            return [
                'id' => $userData['userId'] ?? null,
                'name' => $userData['displayName'] ?? null,
                'email' => $userData['email'] ?? null,
                'picture' => $userData['pictureUrl'] ?? null,
            ];
        } catch (RequestException $e) {
            $error_description = $e->getMessage();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $content = json_decode($response->getBody()->getContents(), true);

                if (Arr::has($content, 'message')) {
                    $error_description = $content['message'];
                } else {
                    $error_description = "API returned error with status code: $statusCode";
                }
            }

            throw new \Exception($error_description, StatusCode::SOCIAL_LOGIN_ERROR->value);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), StatusCode::SOCIAL_LOGIN_ERROR->value);
        }
    }

    /**
     * @throws Exception
     */
    public function getGoogleAuth($accessToken)
    {
        $endpoint = 'https://oauth2.googleapis.com/tokeninfo';
        $params = [
            'id_token' => $accessToken,
        ];

        try {
            $client = new Client();
            $response = $client->get($endpoint, ['query' => $params]);
            $userData = json_decode($response->getBody(), true);

            return [
                'id' => $userData['sub'] ?? null,
                'name' => $userData['name'] ?? null,
                'email' => $userData['email'] ?? null,
                'picture' => $userData['picture'] ?? null,
            ];
        } catch (RequestException $e) {
            $error_description = $e->getMessage();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $content = json_decode($response->getBody()->getContents(), true);

                if (Arr::has($content, 'error_description')) {
                    $error_description = $content['error_description'];
                } else {
                    $error_description = "API returned error with status code: $statusCode";
                }
            }

            throw new \Exception($error_description, StatusCode::SOCIAL_LOGIN_ERROR->value);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), StatusCode::SOCIAL_LOGIN_ERROR->value);
        }
    }

    /**
     * @throws Exception
     */
    public function auth($type, $access_token)
    {
        return match ($type) {
            self::FACEBOOK => $this->getFacebookAuth($access_token),
            self::LINE => $this->getLineAuth($access_token),
            self::GOOGLE => $this->getGoogleAuth($access_token),
            default => throw new \Exception('error provider.', StatusCode::SOCIAL_LOGIN_UNKNOWN_TYPE->value),
        };
    }

    public function checkoutSocailAccount($provider_name, $accessToken)
    {
        $providerUser = $this->auth($provider_name, $accessToken);

        if (!$providerUser['id']) {
            return false;
        }

        $socialAccount = SocialAccount::where('provider_name', $provider_name)->where('provider_id', $providerUser['id'])->first();
        if(is_null($socialAccount)){
            return false;
        }

        return true;
    }

    public function bindSocialAccount($provider_name, $accessToken){

        $providerUser = $this->auth($provider_name, $accessToken);
        if (!$providerUser['id']) {
            throw new ErrException('找不到社群帳號 ID');
        }

        $socialAccount = SocialAccount::where('provider_name', $provider_name)->where('provider_id', $providerUser['id'])->first();
        if ($socialAccount) {
            throw new ErrException('社群帳號已經完成註冊');
        }

        $user = Auth::user();
        $socialAccount = $user->social_accounts()->create([
            'provider_name' => $provider_name,
            'provider_id' => $providerUser['id'],
        ]);
       
    }
    
}
