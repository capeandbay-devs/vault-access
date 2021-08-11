<?php

namespace CapeAndBay\VaultAccess\Actions\Vaults;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ixudra\Curl\Facades\Curl;
use Lorisleiva\Actions\Concerns\AsAction;

class ItemDetails
{
    use  AsAction {
        __invoke as protected invokeFromLaravelActions;
    }

    public function __invoke()
    {

    }

    public function rules(): array
    {
        // Validate the user and the location
        return [
            'token' => ['required'],
            'vaultUuid' => ['required'],
            'itemUuid' => ['required'],
        ];
    }

    public function asController(Request $request)
    {
        $data = $request->all();

        if(config('vault-access.enable_caching'))
        {
            $results = false;
            $user = auth()->user();
            if(!is_null($user))
            {
                $_this = $this;
                $results = Cache::remember($user->id.'-vault-item- details-'.$data['vaultUuid'].'-'. $data['itemUuid'], (60 * 5), function() use ($_this, $data){
                    return $_this->handle($data['token'], $data['vaultUuid'], $data['itemUuid']);
                });
            }

            if(!$results)
            {
                Cache::forget($user->id.'-vault-item- details-'.$data['vaultUuid'].'-'. $data['itemUuid']);
            }
        }
        else
        {
            $results = $this->handle($data['token'], $data['vaultUuid'], $data['itemUuid']);
        }

        return $results;
    }

    public function handle(string $token, string $vaultUuid, string $itemUuid)
    {
        $results = false;

        $uri = "/v1/vaults/{$vaultUuid}/items/{$itemUuid}";
        $url = config('vault-access.1password_connect_url').$uri;
        // this function should be callable outside of a route by passing in the token
        // this is where you use the token and call the service
        $response = Curl::to($url)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ])->asJson(true)
            ->get();

        if(is_array($response))
        {
            $results = $response;
        }

        return $results;
    }

    public function jsonResponse($result)
    {
        $results = ['success' => false, 'reason' => 'Invalid Request', 'result'=> $result];
        $code = 500;

        if($result)
        {
            $results = ['success' => true, 'details' => $result];
            $code = 200;
        }

        return response($results, $code);
    }

    public function htmlResponse($result)
    {
        if(env('APP_ENV') !== 'production')
        {
            // @todo - turned off in production, a premade (out of context) view can be passed to test connectivity
        }

        return $this->jsonResponse($result);

    }
}
