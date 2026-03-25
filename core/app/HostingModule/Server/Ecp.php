<?php

namespace App\HostingModule\Server;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\HostingModule\Server\HostingManagerInterface;

class Ecp implements HostingManagerInterface
{

    public function create($hosting)
    {
        try {
            $user = $hosting->user;
            $product = $hosting->product;
            $server = $hosting->server;
            $token = $server->api_token;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get($server->hostname . '/package/name/' . $product->package_name);

            if (@$response->status() != 200) {

                $message = @$response->message();

                return [
                    'success' => false,
                    'message' => $message
                ];
            }

            $package = json_decode($response);

            $createAccountData = (object) [
                "primary_domain" => $hosting->domain,
                "username" => $hosting->username,
                "password" => $hosting->password,
                "email" => $user->email,
                "name" => $user->fullname,
                "package_id" => $package->id,
                "dns_server" => "none",
                "hosting_environment" => "ecp-base",
                "disk_quota" => $package->disk_quota,
                "disk_quota_soft_limit" => $package->disk_quota_soft_limit,
                "memory_reservation" => $package->memory_reservation,
                "cpu_shares" => $package->cpu_shares,
                "cpus" => $package->cpus,
                "memory" => $package->memory,
            ];

            // dd($createAccountData);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $server->api_token,
                'Content-Type' => 'application/json'
            ])->post($server->hostname . '/account', $createAccountData);

            // dd($response->body());
            $account = json_decode($response->body());

            if (@$response->status() != 200) {

                $message = @$account->message;

                return [
                    'success' => false,
                    'message' => $message
                ];
            }

            return [
                'success' => true,
                'message' => $account
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function suspend($data)
    {
        try {
            $hosting = $data['hosting'];
            $server = $hosting->server;
            $request = $data['request'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $server->api_token,
                'Content-Type' => 'application/json'
            ])->patch($server->hostname . '/account/suspend/' . $hosting->username, [
                'suspend_reason' => $request->suspend_reason,
            ]);

            if (@$response->status() != 200) {

                $message = @$response->message();

                return [
                    'success' => false,
                    'message' => $message
                ];
            }

            $hosting->suspend_reason = $request->suspend_reason;
            $hosting->suspend_date = now();
            $hosting->save();

            return [
                'success' => true,
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function unSuspend($hosting)
    {

        try {
            $server = $hosting->server;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $server->api_token,
                'Content-Type' => 'application/json'
            ])->patch($server->hostname . '/account/unsuspend/' . $hosting->username);

            if (@$response->status() != 200) {

                $message = @$response->message();

                return [
                    'success' => false,
                    'message' => $message
                ];
            }

            $hosting->suspend_reason = null;
            $hosting->suspend_date = null;
            $hosting->save();

            return [
                'success' => true
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function terminate($hosting)
    {

        try {
            $server = $hosting->server;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $server->api_token,
                'Content-Type' => 'application/json'
            ])->delete($server->hostname . '/account/' . $hosting->username);

            if (@$response->status() != 200) {

                $message = @$response->message();

                return [
                    'success' => false,
                    'message' => $message
                ];
            }

            $hosting->termination_date = now();
            $hosting->save();

            return [
                'success' => true,
                'message' => 'Account terminated successfully'
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function changePackage($hosting)
    {

        try {
            $server = $hosting->server;
            $product = $hosting->product;
            $token = 'WHM ' . $server->username . ':' . $server->api_token;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get($server->hostname . '/cpsess' . $server->security_token . '/json-api/changepackage?api.version=1&user=' . $hosting->username . '&pkg=' . $product->package_name);

            $response = json_decode($response);
            $responseStatus = $this->whmResponseStatus($response);

            if (!@$responseStatus['success']) {
                $message = @$responseStatus['message'];

                $this->adminNotification($hosting, @$message);

                return [
                    'success' => false,
                    'message' => @$message
                ];
            }

            $hosting->package_name = $product->package_name;
            $hosting->save();

            return [
                'success' => true
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function changePassword($hosting)
    {

        try {
            $server = $hosting->server;
            $token = 'WHM ' . $server->username . ':' . $server->api_token;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get($server->hostname . '/cpsess' . $server->security_token . '/json-api/passwd?api.version=1&user=' . $hosting->username . '&password=' . $hosting->password);

            $response = json_decode($response);
            $responseStatus = $this->whmResponseStatus($response);

            if (!@$responseStatus['success']) {
                $message = @$responseStatus['message'];

                $this->adminNotification($hosting, @$message);

                return [
                    'success' => false,
                    'message' => @$message
                ];
            }

            return [
                'success' => true,
                'message' => 'Password changed successfully'
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function accountSummary($hosting)
    {

        try {
            $server = $hosting->server;
            $token = 'WHM ' . $server->username . ':' . $server->api_token;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get($server->hostname . '/cpsess' . $server->security_token . '/json-api/accountsummary?api.version=1&user=' . $hosting->username);

            $response = json_decode($response);
            $data = @$response->data->acct[0];

            return [
                'raw_data' => $data,
                'processed_data' => $this->getProcessedAccountSummary(@$response->data->acct[0]),
            ];
        } catch (\Exception  $error) {
            Log::error($error->getMessage());
        }
    }

    public function loginServer($server)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($server->hostname . '/auth/login', [
                'username' => $server->username,
                'password' => $server->password,
            ]);

            $responseData = json_decode($response);

            if (@$response->status() != 200) {
                $message = @$responseData->message;

                if ($server->id) {
                    $this->adminNotification(null, @$message, urlPath('admin.server.edit.page', $server->id));
                }

                return [
                    'success' => false,
                    'message' => @$message
                ];
            }

            $url = '';
            $access_token = $responseData->access_token;
            if (isset($server->host)) {
                $url = $server->protocol . $server->host . ':2325' . '/?token=' . $access_token;
            }
            return [
                'success' => true,
                'url' => $url
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    public function loginAccount($hosting)
    {

        try {
            $server = $hosting->server;
            $token = 'Basic ' . base64_encode($server->username . ':' . $server->password);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get($server->hostname . '/json-api/create_user_session?api.version=1&user=' . $hosting->username . '&service=cpaneld');

            $response = json_decode($response);

            if (@$response->cpanelresult->error || !@$response->metadata->result) {
                $message = $response->cpanelresult->data->reason ?? @$response->metadata->reason;

                $this->adminNotification($hosting, @$message);

                return [
                    'success' => false,
                    'message' => @$message
                ];
            }

            $redirectUrl = $response->data->url;
            return [
                'success' => true,
                'url' => $redirectUrl
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    //Trying to get IP address from WHM API
    public function getIP($server)
    {

        try {
            $token = 'WHM ' . $server->username . ':' . $server->api_token;

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->get($server->hostname . '/cpsess' . $server->security_token . '/json-api/accountsummary?api.version=1&user=' . $server->username);

            $response = json_decode(@$response);
            return @$response->data->acct[0]->ip ?? null;
        } catch (\Exception  $error) {
            Log::error($error->getMessage());
        }
    }

    public function getPackage($serverGroup)
    {

        try {
            $packages = [];
            $servers = $serverGroup->servers;

            foreach ($servers as $server) {

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $server->api_token,
                    'Content-Type' => 'application/json'
                ])->get($server->hostname . '/package');

                if (@$response->status() != 200) {

                    $message = @$response->message();

                    return [
                        'success' => false,
                        'message' => $message
                    ];
                }

                $response = json_decode($response);

                $packages[$server->id] = array_column(@$response, 'name');
            }


            return [
                'success' => true,
                'data' => $packages
            ];
        } catch (\Exception  $error) {
            return [
                'success' => false,
                'message' => $error->getMessage()
            ];
        }
    }

    protected function getProcessedAccountSummary($accountSummary)
    {

        $summary = [];
        $selectedKey = [
            "outgoing_mail_suspended",
            "backup",
            "user",
            "plan",
            "maxpop",
            "legacy_backup",
            "max_defer_fail_percentage",
            "maxftp",
            "max_emailacct_quota",
            "uid",
            "maxsql",
            "theme",
            "suspendreason",
            "diskused",
            "domain",
            "ip",
            "maxparked",
            "maxaddons",
            "temporary",
            "min_defer_fail_to_trigger_protection",
            "is_locked",
            "startdate",
            "unix_startdate",
            "maxlst",
            "partition",
            "email",
            "outgoing_mail_hold",
            "disklimit",
            "maxsub",
            "suspended",
            "inodeslimit",
            "shell",
            "mailbox_format",
            "inodesused",
            "max_email_per_hour",
            "owner",
            "suspendtime"
        ];

        foreach ($selectedKey as $key) {
            if (isset($accountSummary->$key)) {
                $summary[$key] = $accountSummary->$key;
            } else {
                $summary[$key] = null;
            }
        }

        $used = (int) @$accountSummary->diskused;
        $limit = (int) @$accountSummary->disklimit;

        if ($limit == 'unlimited' || $used == 0) {
            $used = 0;
            $limit = 1;
        }

        $diskUsagePercent = ($used / $limit) * 100;
        $summary['disk_usage_percent'] = $accountSummary ? round($diskUsagePercent, 2) . '%' : null;

        return $summary;
    }

    protected function whmResponseStatus($response)
    {

        $success = true;
        $message = null;

        if ($response->metadata->result == 0) {

            $success = false;

            if (str_contains($response->metadata->reason, '. at') !== false) {
                $message = explode('. at', $response->metadata->reason)[0];
            } else {
                $message = $response->metadata->reason;
            }
        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }

    protected function adminNotification($data = null, $message, $url = null)
    {
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = @$data->user_id ?? 0;
        $adminNotification->title = gettype($message) == 'array' ? implode('. ', $message) : $message;
        $adminNotification->api_response = 1;
        $adminNotification->click_url = $url ? $url : urlPath('admin.order.hosting.details', $data->id);
        $adminNotification->save();
    }
}
