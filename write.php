<?php
require_once 'config.php';

write_to_sheet('14CVguwTrnW-JH8m4EiXEzhMAAtax4k0aaka8jYP5WDc');

function write_to_sheet($spreadsheetId = '')
{

    $client = new Google_Client();

    $db = new DB();

    $arr_token = (array) $db->get_access_token();
    $accessToken = array(
        'access_token' => $arr_token['access_token'],
        'expires_in' => $arr_token['expires_in'],
    );

    $client->setAccessToken($accessToken);

    $service = new Google_Service_Sheets($client);

    try {
        $range = 'A1:B1';
        $values = [
            [
                'clark',
                'kent',
            ],
        ];
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];
        $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        //$result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
        //printf("%d cells updated.", "");
        echo "fin";
    } catch (Exception $e) {
        if (401 == $e->getCode()) {
            $refresh_token = $db->get_refersh_token();

            $client = new GuzzleHttp\Client(['base_uri' => 'https://accounts.google.com']);

            $response = $client->request('POST', '/o/oauth2/token', [
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "client_id" => GOOGLE_CLIENT_ID,
                    "client_secret" => GOOGLE_CLIENT_SECRET,
                ],
            ]);

            $data = (array) json_decode($response->getBody());
            $data['refresh_token'] = $refresh_token;

            $db->update_access_token(json_encode($data));

            write_to_sheet($spreadsheetId);
        } else {
            echo $e->getMessage(); //print the error just in case your data is not added.
        }
    }
}
