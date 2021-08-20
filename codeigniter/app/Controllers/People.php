<?php

namespace App\Controllers;

use App\Models\PeopleModel;
use App\Models\PushNotificationsModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class People extends BaseController
{
    private $_peopleModel, $_session, $_pushNotificationsModel;

    public function __construct()
    {
        $this->_peopleModel = new PeopleModel();
        $this->_pushNotificationsModel = new PushNotificationsModel();
        $this->_session = \Config\Services::session();
    }

    public function index(): string
    {
        $data['people'] = $this->_peopleModel->getPeople();
        return view('people', $data);
    }

    public function addPerson(): string
    {
        return view('addPerson');
    }

    public function addPerson_Validation()
    {
        helper(['form', 'url']);

        $error = $this->validate([
            'new-prename' => 'required',
            'new-surname' => 'required',
            'new-street' => 'required',
            'new-postcode' => 'required|min_length[5]|max_length[5]|numeric',
            'new-city' => 'required'
        ],
            [
                'new-prename' => [
                    'required' => 'A prename is required'
                ],
                'new-surname' => [
                    'required' => 'A surname is required'
                ],
                'new-streetname' => [
                    'required' => 'A street is required'
                ],
                'new-postcode' => [
                    'required' => 'A postcode is required',
                    'min_length' => 'Postcode must be of length 5',
                    'max_length' => 'Postcode must be of length 5',
                    'numeric' => 'Postcode can only consist of numbers'
                ],
                'new-city' => [
                    'required' => 'A city is required'
                ],
            ]);

        if (!$error) {
            return view('addPerson', ['error' => $this->validator]);
        } else {
            $id = $this->_peopleModel->addPerson(
                $this->request->getVar('new-prename'),
                $this->request->getVar('new-surname'),
                $this->request->getVar('new-street'),
                $this->request->getVar('new-postcode'),
                $this->request->getVar('new-city'),
                $this->_session->get('token')
            );


            if (!empty($id)) {
                $this->_session->setFlashdata('success', 'Person added');

                $subscribers = $this->_pushNotificationsModel->getAllSubscribers();
                foreach ($subscribers as $row) {
                    $keys_auth = array(
                        "contentEncoding" => "aesgcm",
                        "endpoint" => $row->endpoint,
                        "keys" => array(
                            "auth" => $row->auth,
                            "p256dh" => $row->p256dh
                        )
                    );
                    $this->sendMessage($keys_auth, $row->endpoint, "added", $this->request->getVar('new-prename'), $this->request->getVar('new-surname'));
                }


            } else {
                $this->_session->setFlashdata('error', 'There was a mistake adding a person.');
            }


            return $this->response->redirect(site_url("people"));
        }
    }

    function editPerson($id = null): string
    {
        $data['person'] = $this->_peopleModel->getSinglePerson($id);
        return view("editPerson", $data);
    }

    function editPerson_Validation()
    {
        helper(['form', 'url']);

        $error = $this->validate([
            'edit-prename' => 'required',
            'edit-surname' => 'required',
            'edit-street' => 'required',
            'edit-postcode' => 'required|min_length[5]|max_length[5]|numeric',
            'edit-city' => 'required'
        ],
            [
                'edit-prename' => [
                    'required' => 'A prename is required'
                ],
                'edit-surname' => [
                    'required' => 'A surname is required'
                ],
                'edit-streetname' => [
                    'required' => 'A street is required'
                ],
                'edit-postcode' => [
                    'required' => 'A postcode is required',
                    'min_length' => 'Postcode must be of length 5',
                    'max_length' => 'Postcode must be of length 5',
                    'numeric' => 'Postcode can only consist of numbers'
                ],
                'edit-city' => [
                    'required' => 'A city is required'
                ],
            ]);

        if (!$error) {
            $id = $this->request->getVar('id');
            $data['person'] = $this->_peopleModel->getSinglePerson($id);
            $data['error'] = $this->validator;
            return view('editPerson', $data);
        } else {
            $this->_peopleModel->updatePerson(
                $this->request->getVar('id'),
                $this->request->getVar('edit-prename'),
                $this->request->getVar('edit-surname'),
                $this->request->getVar('edit-street'),
                $this->request->getVar('edit-postcode'),
                $this->request->getVar('edit-city'),
                $this->_session->get('token')
            );


            if (!empty($this->request->getVar('id'))) {
                $this->_session->setFlashdata('success', 'Person updated');

                $subscribers = $this->_pushNotificationsModel->getAllSubscribers();
                foreach ($subscribers as $row) {
                    $keys_auth = array(
                        "contentEncoding" => "aesgcm",
                        "endpoint" => $row->endpoint,
                        "keys" => array(
                            "auth" => $row->auth,
                            "p256dh" => $row->p256dh
                        )
                    );
                    $this->sendMessage($keys_auth, $row->endpoint, "updated", $this->request->getVar('edit-prename'), $this->request->getVar('edit-surname'));
                }


            } else {
                $this->_session->setFlashdata('error', 'There was a mistake adding a person.');
            }


            return $this->response->redirect(site_url("people"));
        }
    }

    function deletePerson($id): ResponseInterface
    {
        $person = $this->_peopleModel->getSinglePerson($id);

        if (!empty($id)) {
            $this->_session->setFlashdata('success', 'Person deleted');

            $subscribers = $this->_pushNotificationsModel->getAllSubscribers();
            foreach ($subscribers as $row) {
                $keys_auth = array(
                    "contentEncoding" => "aesgcm",
                    "endpoint" => $row->endpoint,
                    "keys" => array(
                        "auth" => $row->auth,
                        "p256dh" => $row->p256dh
                    )
                );
                $this->sendMessage($keys_auth, $row->endpoint, "deleted", $person->prename, $person->surname);
            }


        } else {
            $this->_session->setFlashdata('error', 'There was a mistake deleting a person.');
        }
        $this->_peopleModel->deletePerson($id);
        return $this->response->redirect(site_url("people"));
    }


    /* ------------------------------------------ Web Push Notifications ---------------------------------------------------- */


    protected function sendMessage($keys_auth, $endpoint, $message, $prename, $surname)
    {
        $subscription = Subscription::create($keys_auth);

        $auth = array(
            'VAPID' => array(
                'subject' => 'PHP Codeigniter Web Push Notification',
                'publicKey' => env('public_key'),
                'privateKey' => env('private_key')
            )
        );

        $webPush = new WebPush($auth);

        $options = [
            'title' => 'A person has been ' . $message,
            'body' => $prename . ' ' . $surname . ' has been ' . $message,
            'icon' => base_url() . '/icon/icon128.png',
            'badge' => base_url() . '/icon/icon128.png',
            'url' => 'http://localhost'
        ];
        $report = $webPush->sendOneNotification(
            $subscription,
            json_encode($options)
        );

        if ($report->isSuccess()) {
            echo "[v] Message sent successfully for subscription {$endpoint}";
        } else {
            echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
        }
    }

    public function push_subscription()
    {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            if (!isset($decoded['endpoint'])) {
                echo 'Error: not a subscription';
                return;
            }

            $method = $_SERVER['REQUEST_METHOD'];


            switch ($method) {
                case 'POST':
                    $subscribers = $this->_pushNotificationsModel->getSubscribersByEndpoint($decoded['endpoint']);
                    try {
                        if (empty($subscribers)) {
                            if ($this->_pushNotificationsModel->insertSubscriber($decoded['endpoint'], $decoded['authToken'], $decoded['publicKey'])) {
                                echo 'Subscription successful.';
                            } else {
                                echo 'Sorry there is some problem';
                            }
                        }
                    } catch (Exception $error) {
                        echo 'Sorry there has been an error processing your request!';
                    }
                    break;
                case 'PUT':
                    $subscribers = $this->_pushNotificationsModel->getSubscribersByEndpoint($decoded['endpoint']);
                    print_r($subscribers);
                    try {
                        if ($subscribers[0]->id !== NULL) {
                            if ($this->_pushNotificationsModel->updateSubscriber($subscribers[0]->id, $decoded['endpoint'], $decoded['authToken'], $decoded['publicKey'])) {
                                echo 'Subscription updated successful.';
                            } else {
                                echo 'Sorry there is some problem';
                            }
                        }
                    } catch (Exception $error) {
                        echo 'Sorry there has been an error processing your request!';
                    }
                    break;
                case 'DELETE':
                    $subscribers = $this->_pushNotificationsModel->getSubscribersByEndpoint($decoded['endpoint']);
                    print_r($subscribers);
                    try {
                        if (!empty($subscribers[0]->id)) {
                            if ($this->_pushNotificationsModel->deleteSubscriber($subscribers[0]->id)) {
                                echo 'Unsubscribtion successful.';
                            } else {
                                echo 'Sorry there is some problem';
                            }
                        }
                    } catch (Exception $error) {
                        echo 'Sorry there has been an error processing your request!';
                    }
                    break;
                default:
                    echo 'Error: method not handled';
                    return;
            }
        }
    }


    public function send_push_notification()
    {
        $subscribers = $this->_pushNotificationsModel->getAllSubscribers();

        foreach ($subscribers as $row) {

            $data = array(
                "contentEncoding" => "aesgcm",
                "endpoint" => $row->endpoint,
                "keys" => array(
                    "auth" => $row->auth,
                    "p256dh" => $row->p256dh
                )
            );

            $subscription = Subscription::create($data);

            $auth = array(
                'VAPID' => array(
                    'subject' => 'PHP Codeigniter Web Push Notification',
                    'publicKey' => env('public_key'),
                    'privateKey' => env('private_key')
                )
            );

            $webPush = new WebPush($auth);

            $options = [
                'title' => 'Test',
                'body' => 'This is a body',
                'icon' => base_url() . '/icon/icon128.png',
                'badge' => base_url() . '/icon/icon128.png',
                'url' => 'http://localhost'
            ];
            $report = $webPush->sendOneNotification(
                $subscription,
                json_encode($options)
            );

            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                echo "[v] Message sent successfully for subscription {$endpoint}";
            } else {
                echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
            }
        }
    }
}
