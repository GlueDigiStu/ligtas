<?php
class DoceboClass
{
    public function __construct()
    {
    }

    /**
     * Add a user to the Ligtas Docebo LMS
     *
     * @return mixed
     */
    public function add_user_to_LMS($first_name, $last_name, $email_address) {

        // Check that the user does not already exist
        $user_id = $this->search_users($email_address);
        if($user_id) return $user_id;

        // Build data packet for new user enrollment
        $data = [];
        $data["userid"] = $email_address;
        $data["email"] =  $email_address;
        $data["password"] =  'password1';
        $data["firstname"] = $first_name;
        $data["lastname"] = $last_name;
        $data["force_change"] = 1; // require user to change password
        $data["level"] = 6; // user
        $data["send_notification_email"] = true;

        // This works in explorer...
        // {
        //     "userid": "a@b.com",
        //     "email": "a@b.com",
        //     "password": "ChickenTunaFish!",
        //     "firstname": "KIller",
        //     "lastname": "Cat",
        //     "force_change": 1,
        //     "level": 6,
        //     "send_notification_email": true,
        //     "select_orgchart": {
        //       "375": 1
        //     }
        //   }
        
        // Check that we have a branch to assign them to
        if(!$branch_id = $this->_get_branch_id('Auto-Enrolled')) return false; // should be 375
		// ##### MAKE SURE THIS GETS ADDED AND DOES NOT THROW AN ERROR #####
        $data["select_orgchart"] = array($branch_id => 1);

        // The URL needed to add user
        $url = 'https://ligtas.yourlms.net/manage/v1/user';

        // Get a token
        $token = $this->_get_token();

        // Encode this json blob because it has a nested array (select_orgchart) // SUPER IMPORTANT
        $encoded_data = json_encode($data);

        // Request the user list from the Docebo API
        $response = $this->_post_API($url, $encoded_data, $token);

        // Did we get a sensible output?
        if (is_array($response)) {
            // Something went wrong
            return false;
        }

        // Get the user-id from the response
        $user_info = json_decode($response);

        // Did we get anything?
        if (property_exists($user_info, 'data')) {
            // Return the branch ID
            return $user_info->data->user_id;
        } else {
            return false;
        }
    }

    /**
     * Add a user to an LMS course
     *
     * @param  int $course_id
     * @param  int $user_id
     * @param  int $duration
     * @return boolean
     */
    public function add_user_to_LMS_course($course_id, $user_id, $duration) {

        // Check we have valid inputs
        if(!$this->_validate_course_id($course_id)) return false;

        // Work out the start / stop dates for $duration
        $interval = new \DateInterval('P'.$duration.'D');
        $start = new DateTimeImmutable();
        $end = $start->add($interval);

        // Build data packet
        $data["level"] = 3;
        $data["date_begin_validity"] = $start->format('Y-m-j');
        $data["date_expire_validity"] = $end->format('Y-m-d');

        // The URL needed to enroll user in a course
        $url = 'https://ligtas.yourlms.net/learn/v1/enrollments/'.$course_id.'/'.$user_id;

        // Get a token
        $token = $this->_get_token();

        // Try to enroll the user using the Docebo API
        $response = $this->_post_API($url, $data, $token);

        // I DON'T THINK THIS ERROR HANDLING MAKES ANY SENSE? REWRITE THIS SHIT ASAP - MARC
        // Did we get a sensible output?
        if (is_array($response)) {
            // Something went wrong
            return false;
        }

        // Get the user-list from the response
        $enroll_status = json_decode($response);

        // Now check to see if we got a positive result
        // Either we got enrolled, or we are already enrolled ... 
        $enrolled = property_exists($enroll_status,'data') 
                    && (
                        (property_exists($enroll_status->data,'enrolled') 
                        && $enroll_status->data->enrolled[0]->id_user > 0)
                        ||
                        (property_exists($enroll_status->data,'errors') 
                        && $enroll_status->data->errors->existing_enrollments[0]->user_id > 0)
                    );
        return $enrolled;
    }

    /**
     * Add a user to multiple LMS courses
     *
     * @param  array $course_ids
     * @param  int $user_id
     * @param  int $duration
     * @return boolean
     */

    public function add_user_to_LMS_course_multiple($course_ids, $user_id, $duration) {

        // ADD VALIDATION THAT WORKS FOR MULTIPLE COURSES NICELY //
        // Check we have valid inputs
        // if(!$this->_validate_course_id($course_id)) return false;

        // Work out the start / stop dates for $duration
        $interval = new \DateInterval('P'.$duration.'D');
        $start = new DateTimeImmutable();
        $end = $start->add($interval);

        // Build data packet
        $data["course_ids"] = $course_ids;
        $data["user_ids"] = array($user_id);
        $data["level"] = 3;
        $data["date_begin_validity"] = $start->format('Y-m-j');
        $data["date_expire_validity"] = $end->format('Y-m-d');

        // The URL needed to enroll user in a course
        $url = 'https://ligtas.yourlms.net/learn/v1/enrollments';

        // Get a token
        $token = $this->_get_token();

        // Encode this json blob because it has nested arrays // SUPER IMPORTANT
        $encoded_data = json_encode($data);

        // Try to enroll the user using the Docebo API
        $response = $this->_post_API($url, $encoded_data, $token);

        // Response looks like this:
        // {
        //     "data": {
        //         "enrolled": [
        //             {
        //                 "id_user": 39054,
        //                 "id_course": 397,
        //                 "waiting": false
        //             },
        //             {
        //                 "id_user": 39054,
        //                 "id_course": 410,
        //                 "waiting": false
        //             },
        //             {
        //                 "id_user": 39054,
        //                 "id_course": 418,
        //                 "waiting": false
        //             }
        //         ],
        //         "errors": []
        //     },
        //     "version": "1.0.0",
        //     "_links": []
        // }

        // I DON'T THINK THIS ERROR HANDLING MAKES ANY SENSE? REWRITE THIS SHIT ASAP - MARC //
        // Did we get a sensible output?
        // if (is_array($response)) {
        //     // Something went wrong
        //     return false;
        // }

        // Get the user-list from the response
        $enroll_status = json_decode($response);

        // Basic positive check - if enrolled counts = $course_ids length, we must be enrolled in everything required
        $enrolled_count = property_exists($enroll_status->data, "enrolled") ? count($enroll_status->data->enrolled) : 0;
        $already_enrolled_count = 0;
        if (!empty($enroll_status->data->errors)) {
            if (property_exists($enroll_status->data->errors, "existing_enrollments")) {
                $already_enrolled_count = count($enroll_status->data->errors->existing_enrollments);
            }
        }

        if (count($course_ids) === ($enrolled_count + $already_enrolled_count)) {
            error_log("$user_id Fully enrolled");
            return true;
        } else {
            error_log("Error in multiple enrollments for $user_id");
            return false;
        }
    }

    /**
     * Get a list of current Ligtas Docebo users
     *
     * @return mixed
     */
    public function search_users($search_string) {

        // URL encode search string
        $u_search_string = urlencode($search_string);

        // The URL needed to retrieve user list
        $url = 'https://ligtas.yourlms.net/manage/v1/user?search_text='.$u_search_string;

        // Get a token
        $token = $this->_get_token();

        // Request the user list from the Docebo API
        $response = $this->_get_API($url, $token);

        // Did we get a sensible output?
        if (is_array($response)) {
            // Something went wrong
            return false;
        }

        // Get the user-list from the response
        $user_list = json_decode($response);

        // Now check to see if we got a positive result
        return property_exists($user_list,'data') && $user_list->data->count > 0  ? $user_list->data->items[0]->user_id : false;
    }

    /**
     * Polls the LMS API using cURL
     *
     * @param  string $url
     * @param  array $data
     * @return mixed
     */
    public function _curl_API($url, $data = null, $token = null)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // This converts the return value of exec() to a string
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

            // Do we have any data to send?
            if($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // This is the full data to send
            }

            // Do we have an API token?
            if($token) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [ // Set auth headers
                    'Authorization' => "Bearer " . $token
                ]);
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' ); // Set request method
            }

            // Exec the cURL request
            $content = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if ($content === false) {
                throw new \Exception(curl_error($ch), curl_errno($ch));
            }

            // Check HTTP return code, too; might be something else than 200
            $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Did it work?
            if ($httpReturnCode != 200) {
                // If for some reason the json decode fails, can't push false to array value, so use null coalesing operator ?? to put '' instead.
                $unpacked_error = json_decode($content);
                $error = is_object($unpacked_error) && property_exists($unpacked_error, 'message') ? $unpacked_error->message : $content;
                return $error;
            }
        } catch (\Exception $e) {
            trigger_error(
                sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                ),
                E_USER_ERROR
            );
        } finally {
            // Close curl handle unless it failed to initialize
            if (is_resource($ch)) {
                curl_close($ch);
            }
        }
        return $content;
    }

    /**
     * Check that a Branch with name $key exists, if not create it, and return ID
     *
     * @param  string $search_string
     * @return int|boolean
     */
    public function _get_branch_id($search_string)
    {
        // URL encode search string
        $u_search_string = urlencode($search_string);

        // The URL needed to retrieve user list
        $url = 'https://ligtas.yourlms.net/manage/v1/orgchart?search_text='.$u_search_string;

        // Get a token
        $token = $this->_get_token();

        // Request the user list from the Docebo API
        $response = $this->_get_API($url, $token);

        // Get the user-list from the response
        $branch_list = json_decode($response);

        // Did we get anything?
        if (property_exists($branch_list, 'data') && $branch_list->data->count > 0) {
            // Return the branch ID
            return $branch_list->data->items[0]->id;
        }

        // Didn't get anything so create the branch        
        $data["code"] = $search_string;
        $data["id_parent"] = 0;
        $data["use_secondary_identifier"] = false;

        // The URL needed to create a branch
        $url = 'https://ligtas.yourlms.net/manage/v1/orgchart';

        // See if we can create the branch
        $response = $this->_post_API($url,$data,$token);

        // Get the code if we have one
        $branch_code = json_decode($response);

        // Did we get anything?
        if (property_exists('data', $branch_code)) {
            // Return the branch ID
            return $branch_code->data->items->id;
        } else {
            return false;
        }
    }

    /**
     * GET request to the LMS API using file_get_contents
     *
     * @param  string $url
     * @param  array $data
     * @return mixed
     */

    public function _get_API($url, $token = null)
    {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $token"));
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // FORCE ACCEPTED PROTOCOL
		$get_response = curl_exec($ch);
		curl_close($ch);

		return $get_response;

        // Jelle's old function - Could not work for DD because of HTTP/1 being forced from our server. //

        //     // Build a GET request
        //     $opts = array(
        //         'http' => array(
        //             'method' => 'GET',
        //             'header' => array(
		// 				'Authorization: Bearer '.$token,
		// 				'protocol_version: 1.1'
        // 			),
        //             'ignore_errors' => true
        //         )
        //     );

        //     $context = stream_context_create($opts);
		// 	// error_log("Created context: " . print_r(stream_context_get_options($context), true));

        //     // Try and get a collection
        //     $get_response = file_get_contents($url, false, $context);

		// 	if (isset($http_response_header)) {
		// 		error_log("Response headers: " . print_r($http_response_header, true));
		// 	}

        //     // Did it work?
        //     if (!strstr($http_response_header[0], '200')) {
        //         // If for some reason the json decode fails, can't push false to array value, so use null coalesing operator ?? to put '' instead.
        //         $unpacked_error = json_decode($get_response);
        //         $error = is_object($unpacked_error) && property_exists($unpacked_error, 'message') ? $unpacked_error->message : $get_response;
        //         return $error;
        //     }

        // return $get_response;
    }

    /**
     * POST request to the LMS API using file_get_contents
     *
     * @param  string $url
     * @param  array $data
     * @return mixed
     */
    public function _post_API($url, $data = null, $token = null) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $token")); 
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // FORCE ACCEPTED PROTOCOL
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST' );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$post_response = curl_exec($ch);
		curl_close($ch);

		error_log("CURL POST REQUEST => $post_response");
		return $post_response;

        // Jelle's old function - Could not work for DD because of HTTP/1 being forced from our server. //

        // Build a POST request
        // Docebo only accepts JSON formatted data ... 
        // $opts = array(
        //     'http' => array(
        //         'method' => 'POST',
        //         'header'=>  "Content-Type: application/json\r\n" .
        //                     "Accept: application/json\r\n".
        //                     'Authorization: Bearer '.$token,
        //         'content' => json_encode( $data ),
        //         'ignore_errors' => true
        //     )
        // );
  
        // $context = stream_context_create($opts);

        // // Poll the API
        // $get_response = file_get_contents($url, false, $context);

        // Did it work?
        // if (!strstr($http_response_header[0], '200')) {
        //     // If for some reason the json decode fails, can't push false to array value, so use null coalesing operator ?? to put '' instead.
        //     $unpacked_error = json_decode($get_response);
        //     $error = is_object($unpacked_error) && property_exists($unpacked_error, 'message') ? $unpacked_error->message : $get_response;
        //     return $error;
        // }
        // return $get_response;
    }

    /**
     * Connect to the LMS and get an API token.
     * API tokens last for 14 days, so we cache it for 10
     *
     * @return string
     */
    public function _get_token() {

        // Collect up information required for the token call
        $data = [];
        // The OAuth2 server URL
        $oauth2_server_url = 'https://ligtas.yourlms.net/oauth2/token';
        // Set the client ID and secret
        $data['client_id'] = 'ligtaslocal';
        $data['client_secret'] = '0afcb04259d55265b8d449dd859da1c8975a97ae';
        // Set the grant type
        // $grant_type = 'client_credentials';
        $data['grant_type'] = 'password';
        // Set the scope
        $data['scope'] = 'api';
        // Set the user
        $data['username'] = 'joe@designdough.co.uk';
        $data['password'] = 'zfm*ruj3DFJ2jbw2fyh';

        // Send a POST request to the OAuth2 server to get a token
        $response = $this->_curl_API($oauth2_server_url, $data, null);

        // Get the access token from the response
        $api_key = json_decode($response)->access_token;
    
        return $api_key;
    }

    /**
     * Check that a Course_id exists
     *
     * @param  string $course_id
     * @return boolean
     */
    public function _validate_course_id($course_id)
    {
        // URL encode search string
        $u_course_id = urlencode($course_id);

        // The URL needed to retrieve user list
        $url = 'https://ligtas.yourlms.net/course/v1/courses/'.$u_course_id;

        // Get a token
        $token = $this->_get_token();

        // Search the course list with the Docebo API
        $response = $this->_get_API($url, $token);

        // Get the course-list from the response
        $course_list = json_decode($response);

        // Did we get anything?
        return property_exists($course_list, 'data') && $course_list->data->status == 'published';
    }
}
?>