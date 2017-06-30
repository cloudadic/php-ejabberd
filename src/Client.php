<?php

/*
 * Copyright 2017 Cloudadic Intelligent Solutions India Pvt Ltd.
 * 
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy this software in source code or binary form for use in connection 
 * with the web services and APIs provided by BuzzBoard, Inc.
 * 
 * As with any software that integrates with the BuzzBoard, your use of this software 
 * is subject to the BuzzBoard's Privacy Policy [https://www.buzzboard.com/privacy-policy/].
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

namespace Cloudadic\Ejabberd;

/**
 * Description of EjabberdClient
 *
 * @author prashantbhardwaj
 */
use GuzzleHttp\Client AS GuzzleHttpClient;

class Client {

    private $config;
    private $baseUri = null;
    private $client = null;

    public function __construct($config) {
        $this->config = $config;
        if (isset($this->config['port'])) {
            $this->baseUri = $this->config['host'] . ":" . $this->config['port'];
        } else {
            $this->baseUri = $this->config['host'];
        }
        $this->baseUri .= $this->config['apiEndPoint'];
        $this->client = new GuzzleHttpClient([
            'base_uri' => $this->baseUri
        ]);
    }

    public function addUser($user, $password) {
        $response = $this->client->request('POST', '/api/register', ["json" => ['user' => $user, 'host' => $this->config['domain'], 'password' => $password]]);
        return $response->getBody();
    }

    public function addUserRoster($localuser, $localserver, $user, $nick, $group, $subs) {
        $response = $this->client->request('POST', '/api/add_rosteritem', ['json' => ['localuser' => $localuser, 'localserver' => $localserver, 'user' => $user, 'server' => $this->config['domain'], 'nick' => $nick, 'group' => $group, 'subs' => $subs]]);
        return $response->getBody();
    }

    public function storeDbToBackupFile($file) {
        $respone = $this->client->request('POST', '/api/backup', ['json' => ['file' => $file]]);
        return $respone->getBody();
    }

    public function banAccount($user, $reason) {
        $response = $this->client->request('POST', '/api/ban_account', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'reason' => $reason]]);
        return $response->getBody();
    }

    public function changePassword($user, $newPassword) {
        $response = $this->client->request('POST', '/api/change_password', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'newpass' => $newPassword]]);
        return $response->getBody();
    }

    public function changeRoomOption($name, $service, $option, $value) {
        // Change an option in a MUC room
        $response = $this->client->request('POST', 'api/change_room_option', ['json' => ['name' => $name, 'service' => $service, 'option' => $option, 'value' => $value]]);
        return $response->getBody();
    }

    public function checkAccount($user) {
        //check if account exists
        $response = $this->client->request('POST', 'api/check_account', ['json' => ['user' => $user, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function checkPassword($user, $password) {
        $response = $this->client->request('POST', 'api/check_password', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'password' => $password]]);
        return $response->getBody();
    }

    public function checkPasswordHash($user, $passwordHash, $hashMethod) {
        // Allowed hash methods: md5, sha
        $response = $this->client->request('POST', 'api/check_password_hash', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'passwordhash' => $passwordHash, 'hashmethod' => $hashMethod]]);
        return $response->getBody();
    }

    public function compile($file) {
        // Recompile and reload Erlang source code file
        $response = $this->client->request('POST', 'api/compile', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function connectedUsers() {
        // list of connected users
        $response = $this->client->request('POST', 'api/connected_users', ['json' => []]);
        return $response->getBody();
    }

    public function connectedUsersInfoList() {
        $response = $this->client->request('POST', 'api/connected_users_info', ['json' => []]);
        return $response->getBody();
    }

    public function connectedUsersNumber() {
        $response = $this->client->request('POST', 'api/connected_users_number', ['json' => []]);
        return $response->getBody();
    }

    public function connectedUsersVhost() {
        //Get the list of established sessions in a vhost
        $response = $this->client->request('POST', 'api/connected_users_vhost', ['json' => ['host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function convertToScram() {
        // Convert the passwords in ‘users’ SQL table to SCRAM
        $response = $this->client->request('POST', 'api/convert_to_scram', ['json' => ['host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function convertToYaml($in, $out) {
        // convert_to_scram
        $response = $this->client->request('POST', 'api/convert_to_yaml', ['json' => ['in' => $in, 'out' => $out]]);
        return $response->getBody();
    }

    public function createRoom($name, $service) {
        // Create a MUC room name@service in host
        $response = $this->client->request('POST', '/api/create_room', ['json' => ['name' => $name, 'service' => $service, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function createRoomWithOpts($name, $service, $options = []) {
        // Create a MUC room name@service in host with given options
        if (is_array($options)) {
            $response = $this->client->request('POST', '/api/create_room_with_opts', ['json' => ['name' => $name, 'service' => $service, 'host' => $this->config['domain'], 'options' => $options]]);
            return $response->getBody();
        } else {
            throw new Exception("Options must be an array!");
        }
    }

    public function createRoomsFile($file) {
        // Provide one room JID per line. Rooms will be created after restart
        $response = $this->client->request('POST', '/api/create_rooms_file', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function deleteExpiredMessages() {
        // Delete expired offline messages from database
        $response = $this->client->request('POST', '/api/delete_expired_messages', ['json' => []]);
        return $response->getBody();
    }

    public function deleteMnesia() {
        // Export all tables as SQL queries to a file
        $response = $this->client->request('POST', '/api/delete_mnesia', ['json' => ['host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function deleteOldMamMessages($type, $days) {
        //Delete MAM messages older than DAYS
        //Valid message TYPEs: “chat”, “groupchat”, “all”.
        $response = $this->client->request('POST', '/api/delete_old_mam_messages', ['json' => ['type' => $type, 'days' => $days]]);
        return $response->getBody();
    }

    public function deleteOldMessages($days) {
        // Delete offline messages older than DAYS
        $response = $this->client->request('POST', '/api/delete_old_messages', ['json' => ['days' => $days]]);
        return $response->getBody();
    }

    public function deleteOldusers($days) {
        // Delete users that didn’t log in last days, or that never logged
        $response = $this->client->request('POST', '/api/delete_old_users', ['json' => ['days' => $days]]);
        return $response->getBody();
    }

    public function deleteOldUsersVhost($days) {
        // Delete users that didn’t log in last days in vhost, or that never logged
        $response = $this->client->request('POST', '/api/delete_old_users_vhost', ['json' => ['host' => $this->config['domain'], 'days' => $days]]);
        return $response->getBody();
    }

    public function deleteRosterItem($localuser, $localserver, $user, $server) {
        // Delete an item from a user’s roster (supports SQL)
        $response = $this->client->request('POST', '/api/delete_rosteritem', ['json' => ['localuser' => $localuser, 'localserver' => $localserver, 'user' => $user, 'server' => $server]]);
        return $response->getBody();
    }

    public function destroyRoom($name, $service) {
        // Destroy a MUC room
        $response = $this->client->request('POST', '/api/destroy_room', ['json' => ['name' => $name, 'service' => $service]]);
        return $response->getBody();
    }

    public function destroyRoomsFile($file) {
        // Destroy the rooms indicated in file
        // Provide one room JID per line.
        $response = $this->client->request('POST', '/api/destroy_rooms_file', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function dumpDbToTextFile($file) {
        // Dump the database to text file
        $response = $this->client->request('POST', '/api/dump', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function dumpTableToTextFile($file, $table) {
        // Dump a table to text file
        $response = $this->client->request('POST', '/api/dump_table', ['json' => ['file' => $file, 'table' => $table]]);
        return $response->getBody();
    }

    public function export2Sql($file) {
        // Export virtual host information from Mnesia tables to SQL files
        $response = $this->client->request('POST', '/api/export2sql', ['json' => ['host' => $this->config['domain'], 'file' => $file]]);
        return $response->getBody();
    }

    public function exportPiefxis($dir) {
        // Export data of all users in the server to PIEFXIS files (XEP-0227)
        $response = $this->client->request('POST', '/api/export_piefxis', ['json' => ['dir' => $dir]]);
        return $response->getBody();
    }

    public function exportPiefxisHost($dir) {
        // export_piefxis_host 
        $response = $this->client->request('POST', '/api/export_piefxis_host', ['json' => ['dir' => $dir, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function genHtmlDocForCommands($file, $regexp, $examples) {
        // Generates html documentation for ejabberd_commands
        $response = $this->client->request('POST', '/api/gen_html_doc_for_commands', ['json' => ['file' => $file, 'regexp' => $regexp, 'examples' => $examples]]);
        return $response->getBody();
    }

    public function genMarkdownDocForCommands($file, $regexp, $examples) {
        // Generates markdown documentation for ejabberd_commands
        $response = $this->client->request('POST', '/api/gen_markdown_doc_for_commands', ['json' => ['file' => $file, 'regexp' => $regexp, 'examples' => $examples]]);
        return $response->getBody();
    }

    public function getCookie() {
        // Get the Erlang cookie of this node
        $response = $this->client->request('POST', '/api/get_cookie', ['json' => []]);
        return $response->getBody();
    }

    public function getLastActivityInfo($user) {
        // Get last activity information (timestamp and status)
        // Timestamp is the seconds since1970-01-01 00:00:00 UTC, for example: date +%s
        $response = $this->client->request('POST', '/api/get_last', ['json' => ['user' => $user, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function getLoglevel() {
        // Get the current loglevel
        $response = $this->client->request('POST', '/api/get_loglevel', ['json' => []]);
        return $response->getBody();
    }

    public function getOfflineCount() {
        //Get the number of unread offline messages
        $response = $this->client->request('POST', '/api/get_offline_count', ['json' => []]);
        return $response->getBody();
    }

    public function getRoomAffiliations($name, $service) {
        // Get the list of affiliations of a MUC room
        $response = $this->client->request('POST', '/api/get_room_affiliations', ['json' => ['name' => $name, 'service' => $service]]);
        return $response->getBody();
    }

    public function getRoomOccupants($name, $service) {
        // Get the list of occupants of a MUC room
        $response = $this->client->request('POST', '/api/get_room_occupants', ['json', ['name' => $name, 'service' => $service]]);
        return $response->getBody();
    }

    public function getRoomOccupantsNumber($name, $service) {
        // Get the number of occupants of a MUC room
        $response = $this->client->request('POST', '/api/get_room_occupants_number', ['json' => ['name' => $name, 'service' => $service]]);
        return $response->getBody();
    }

    public function getRoomOptions($name, $service) {
        // Get options from a MUC room
        $response = $this->client->request('POST', '/api/get_room_options', ['json' => ['name' => $name, 'service' => $service]]);
        return $response->getBody();
    }

    public function getRoster() {
        // Get roster of a local user
        $response = $this->client->request('POST', '/api/get_roster', ['json' => []]);
        return $response->getBody();
    }

    public function getSubscribers($name, $service) {
        // List subscribers of a MUC conference
        $response = $this->client->request('POST', '/api/get_subscribers', ['json' => ['name' => $name, 'service' => $service]]);
        return $response->getBody();
    }

    public function getUserRooms($user) {
        // Get the list of rooms where this user is occupant
        $response = $this->client->request('POST', '/api/get_user_rooms', ['json' => ['user' => $user, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function getVcard($user, $name) {
        // Get content from a vCard field
        $response = $this->client->request('POST', '/api/get_vcard', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'name' => $name]]);
        return $response->getBody();
    }

    public function getVcard2($user, $name, $subname) {
        // Get content from a vCard field
        $response = $this->client->request('POST', '/api/get_vcard2', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'name' => $name, 'subname' => $subname]]);
        return $response->getBody();
    }

    public function getVcard2multi($user, $name, $subname) {
        // Get multiple contents from a vCard field
        $response = $this->client->request('POST', '/api/get_vcard2_multi', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'name' => $name, 'subname' => $subname]]);
        return $response->getBody();
    }

    public function importDir($file) {
        // Import users data from jabberd14 spool dir
        $response = $this->client->request('POST', '/api/import_dir', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function importFile($file) {
        // Import user data from jabberd14 spool file
        $response = $this->client->request('POST', '/api/import_file', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function importPiefxis($file) {
        // Import users data from a PIEFXIS file (XEP-0227)
        $response = $this->client->request('POST', '/api/import_piefxis', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function importProsody($dir) {
        // Import data from Prosody
        $response = $this->client->request('POST', '/api/import_prosody', ['json' => ['dir' => $dir]]);
        return $response->getBody();
    }

    public function incomingS2sNumber() {
        // Number of incoming s2s connections on the node
        $response = $this->client->request('POST', '/api/incoming_s2s_number', ['json' => []]);
        return $response->getBody();
    }

    public function installFallback($file) {
        // Install the database from a fallback file
        $response = $this->client->request('POST', '/api/install_fallback', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function joinCluster($node) {
        // Join this node into the cluster handled by Node
        $response = $this->client->request('POST', '/api/join_cluster', ['json' => ['node' => $node]]);
        return $response->getBody();
    }

    public function kickSession($user, $resource, $reason) {
        // Kick a user session
        $response = $this->client->request('POST', '/api/kick_session', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'resource' => $resource, 'reason' => $reason]]);
        return $response->getBody();
    }

    public function kickUser($user) {
        // Disconnect user’s active sessions
        $resonse = $this->client->request('POST', '/api/kick_user', ['json' => ['user' => $user, 'host' => $this->config['domain']]]);
        return $resonse->getBody();
    }

    public function leaveCluster($node) {
        // Remove node handled by Node from the cluster
        $response = $this->client->request('POST', '/api/leave_cluster', ['json' => ['node' => $node]]);
        return $response->getBody();
    }

    public function listCluster() {
        // List nodes that are part of the cluster handled by Node
        $response = $this->client->request('POST', '/api/list_cluster', ['json' => []]);
        return $response->getBody();
    }

    public function loadDbFromTextFile($file) {
        // Restore the database from text file
        $response = $this->client->request('POST', '/api/load', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function mnesiaChangeNodename($oldNodeName, $newNodeName, $oldBackup, $newBackup) {
        // Change the erlang node name in a backup file
        $response = $this->client->request('POST', '/api/mnesia_change_nodename', ['json' => ['oldnodename' => $oldNodeName, 'newnodename' => $newNodeName, 'oldbackup' => $oldBackup, 'newbackup' => $newBackup]]);
        return $response->getBody();
    }

    public function moduleCheck($module) {
        $response = $this->client->request('POST', '/api/module_check', ['json' => ['module' => $module]]);
        return $response->getBody();
    }

    public function moduleInstall($module) {
        $response = $this->client->request('POST', '/api/module_install', ['json' => ['module' => $module]]);
        return $response->getBody();
    }

    public function moduleUninstall($module) {
        $response = $this->client->request('POST', '/api/module_uninstall', ['json' => ['module' => $module]]);
        return $response->getBody();
    }

    public function moduleUpgrade($module) {
        $response = $this->client->request('POST', '/api/module_upgrade', ['json' => ['module' => $module]]);
        return $response->getBody();
    }

    public function modulesAvailable() {
        $response = $this->client->request('POST', '/api/modules_available', ['json' => []]);
        return $response->getBody();
    }

    public function modulesInstalled() {
        $response = $this->client->request('POST', '/api/modules_installed', ['json' => []]);
        return $response->getBody();
    }

    public function modulesUpdateSpecs() {
        $response = $this->client->request('POST', '/api/modules_update_specs', ['json' => []]);
        return $response->getBody();
    }

    public function mucOnlineRooms() {
        $response = $this->client->request('POST', '/api/muc_online_rooms', ['json' => ['host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function mucUnregisterNick($nick) {
        // Unregister the nick in the MUC service
        $response = $this->client->request('POST', '/api/muc_unregister_nick', ['json' => ['nick' => $nick]]);
        return $response->getBody();
    }

    public function numActiveUsers($days) {
        // Get number of users active in the last days
        $response = $this->client->request('POST', '/api/num_active_users', ['json' => ['host' => $this->config['domain'], 'days' => $days]]);
        return $response->getBody();
    }

    public function numResources($user) {
        // Get the number of resources of a user
        $response = $this->client->request('POST', '/api/num_resources', ['json' => ['user' => $user, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function outgoingS2sNumber() {
        // Number of outgoing s2s connections on the node
        $response = $this->client->request('POST', '/api/outgoing_s2s_number', ['json' => []]);
        return $response->getBody();
    }

    public function privacySet($user, $xmlQuery) {
        // Send a IQ set privacy stanza for a local account
        $response = $this->client->request('POST', '/api/privacy_set', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'xmlquery' => $xmlQuery]]);
        return $response->getBody();
    }

    public function privateGet($user, $element, $ns) {
        // Get some information from a user private storage
        $response = $this->client->request('POST', '/api/private_get', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'element' => $element, 'ns' => $ns]]);
        return $response->getBody();
    }

    public function privateSet($user, $element) {
        // Set to the user private storage
        $response = $this->client->request('POST', '/api/private_set', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'element' => $element]]);
        return $response->getBody();
    }

    public function processRosterItems($action, $subs, $asks, $user, $contacts) {
        // List or delete rosteritems that match filtering options
        $response = $this->client->request('POST', '/api/process_rosteritems', ['json' => ['action' => $action, 'subs' => $subs, 'asks' => $asks, 'users' => $user, 'contacts' => $contacts]]);
        return $response->getBody();
    }

    public function pushAllToAll($group) {
        // Add all the users to all the users of Host in Group
        $response = $this->client->request('POST', '/api/push_alltoall', ['json' => ['host' => $this->config['domain'], 'group' => $group]]);
        return $response->getBody();
    }

    public function pushRoster($file, $user) {
        // Push template roster from file to a user
        $response = $this->client->request('POST', '/api/push_roster', ['json' => ['file' => $file, 'user' => $user, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function pushRosterAll($file) {
        // Push template roster from file to all those users
        $response = $this->client->request('POST', '/api/push_roster_all', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function registeredUsers() {
        // List all registered users in HOST
        $response = $this->client->request('POST', '/api/registered_users', ['json' => ['host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function registeredVhosts() {
        // List all registered vhosts in SERVER
        $response = $this->client->request('POST', '/api/registered_vhosts', ['json' => []]);
        return $response->getBody();
    }

    public function reloadConfig() {
        // Reload config file in memory (only affects ACL and Access)
        $response = $this->client->request('POST', '/api/reload_config', ['json' => []]);
        return $response->getBody();
    }

    public function removeNode($node) {
        // Remove an ejabberd node from Mnesia clustering config
        $response = $this->client->request('POST', '/api/remove_node', ['json' => ['node' => $node]]);
        return $response->getBody();
    }

    public function reopenLog() {
        // Reopen the log files
        $response = $this->client->request('POST', '/api/reopen_log', ['json' => []]);
        return $response->getBody();
    }

    public function resourceNum($user, $num) {
        // Resource string of a session number
        $response = $this->client->request('POST', '/api/resource_num', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'num' => $num]]);
        return $response->getBody();
    }

    public function restart() {
        // Restart ejabberd gracefully
        $response = $this->client->request('POST', '/api/restart', ['json' => []]);
        return $response->getBody();
    }

    public function restore($file) {
        // Restore the database from backup file
        $response = $this->client->request('POST', '/api/restore', ['json' => ['file' => $file]]);
        return $response->getBody();
    }

    public function roomsUnusedDestroy($days) {
        // Destroy the rooms that are unused for many days in host
        $response = $this->client->request('POST', '/api/rooms_unused_destroy', ['json' => ['host' => $this->config['domain'], 'days' => $days]]);
        return $response->getBody();
    }

    public function roomsUnusedList($days) {
        // List the rooms that are unused for many days in host
        $response = $this->client->request('POST', '/api/rooms_unused_list', ['json' => ['host' => $this->config['domain'], 'days' => $days]]);
        return $response->getBody();
    }

    public function rotateLog() {
        // Rotate the log files
        $response = $this->client->request('POST', '/api/rotate_log', ['json' => []]);
        return $response->getBody();
    }

    public function sendDirectInvitations($name, $service, $password, $reason, $user) {
        // Send a direct invitation to several destinations
        $response = $this->client->request('POST', '/api/send_direct_invitations', ['json' => ['name' => $name, 'service' => $service, 'password' => $password, 'reason' => $reason, 'user' => $user]]);
        return $response->getBody();
    }

    public function sendMessage($type, $from, $to, $subject, $body) {
        // Send a message to a local or remote bare of full JID
        $response = $this->client->request('POST', '/api/send_message', ['json' => ['type' => $type, 'from' => $from, 'to' => $to, 'subject' => $subject, 'body' => $body]]);
        return $response->getBody();
    }

    public function sendStanza($from, $to, $stanza) {
        // Send a stanza; provide From JID and valid To JID
        $response = $this->client->request('POST', '/api/send_stanza', ['json' => ['from' => $from, 'to' => $to, 'stanza' => $stanza]]);
        return $response->getBody();
    }

    public function sendStanzaC2s($user, $resource, $stanza) {
        // Send a stanza as if sent from a c2s session
        $response = $this->client->request('POST', '/api/send_stanza_c2s', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'resource' => $resource, 'stanza' => $stanza]]);
        return $response->getBody();
    }

    public function setLast($user, $timestamp, $status) {
        // Set last activity information
        // Timestamp is the seconds since1970-01-01 00:00:00 UTC, for example: date +%s
        $response = $this->client->request('POST', '/api/set_last', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'timestamp' => $timestamp, 'status' => $status]]);
        return $response->getBody();
    }

    public function setLogLevel($loglevel) {
        // Set the loglevel (0 to 5)
        $response = $this->client->request('POST', '/api/set_loglevel', ['json' => ['loglevel' => $loglevel]]);
        return $response->getBody();
    }

    public function setMaster($nodename) {
        // Set master node of the clustered Mnesia tables
        // If you provide as nodename “self”, this node will be set as its own master.
        $response = $this->client->request('POST', '/api/set_master', ['json' => ['nodename' => $nodename]]);
        return $response->getBody();
    }

    public function setNickname($user, $nickname) {
        // Set nickname in a user’s vCard
        $response = $this->client->request('POST', '/api/set_nickname', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'nickname' => $nickname]]);
        return $response->getBody();
    }

    public function setPresence($user, $resource, $type, $show, $status, $priority) {
        // Set presence of a session
        $response = $this->client->request('POST', '/api/set_presence', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'resource' => $resource, 'type' => $type, 'show' => $show, 'status' => $status, 'priority' => $priority]]);
        return $response->getBody();
    }

    public function setRoomAffiliation($name, $service, $jid, $affiliation) {
        // Change an affiliation in a MUC room
        $response = $this->client->request('POST', '/api/set_room_affiliation', ['json' => ['name' => $name, 'service' => $service, 'jid' => $jid, 'affiliation' => $affiliation]]);
        return $response->getBody();
    }

    public function setVcard($user, $name, $content) {
        // Set content in a vCard field
        $response = $this->client->request('POST', '/api/set_vcard', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'name' => $name, 'content' => $content]]);
        return $response->getBody();
    }

    public function setVcard2($user, $name, $subname, $content) {
        // Set content in a vCard subfield
        $response = $this->client->request('POST', '/api/set_vcard2', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'name' => $name, 'subname' => $subname, 'content' => $content]]);
        return $response->getBody();
    }

    public function setVcard2Multi($user, $name, $subname, $contents = []) {
        // Set multiple contents in a vCard subfield
        if (is_array($contents)) {
            $response = $this->client->request('POST', '/api/set_vcard2_multi', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'name' => $name, 'subname' => $subname, 'contents' => $contents]]);
            return $response->getBody();
        } else {
            throw new Exception("contents must be an array!");
        }
    }

    public function srgCreate($group, $name, $description, $display) {
        // Create a Shared Roster Group
        $response = $this->client->request('POST', '/api/srg_create', ['json' => ['group' => $group, 'host' => $this->config['domain'], 'name' => $name, 'description' => $description, 'display' => $display]]);
        return $response->getBody();
    }

    public function srgDelete($group) {
        // Delete a Shared Roster Group
        $response = $this->client->request('POST', '/api/srg_delete', ['json' => ['group' => $group, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function srgGetInfo($group) {
        // Get info of a Shared Roster Group
        $response = $this->client->request('POST', '/api/srg_get_info', ['json' => ['group' => $group, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function srgGetMembers($group) {
        // Get members of a Shared Roster Group
        $response = $this->client->request('POST', '/api/srg_get_members', ['json' => ['group' => $group, 'host' => $this->config[domain]]]);
        return $response->getBody();
    }

    public function srgList() {
        // List the Shared Roster Groups in Host
        $response = $this->client->request('POST', '/api/srg_list', ['json' => ['host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function srgUserAdd($user, $group, $grouphost) {
        // Add the JID user@host to the Shared Roster Group
        $response = $this->client->request('POST', '/api/srg_user_add', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'group' => $group, 'grouphost' => $grouphost]]);
        return $response->getBody();
    }

    public function srgUserDel($user, $group, $grouphost) {
        // Delete this JID user@host from the Shared Roster Group
        $response = $this->client->request('POST', '/api/srg_user_del', ['json' => ['user' => $user, 'host' => $this->config['domain'], 'group' => $group, 'grouphost' => $grouphost]]);
        return $response->getBody();
    }

    public function stats($name) {
        // Get statistical value: registeredusers onlineusers onlineusersnode uptimeseconds processes
        $response = $this->client->request('POST', '/api/stats', ['json' => ['name' => $name]]);
        return $response->getBody();
    }

    public function statsHost($name) {
        // Get statistical value for this host: registeredusers onlineusers
        $response = $this->client->request('POST', '/api/stats_host', ['json' => ['name' => $name, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function status() {
        // Get status of the ejabberd server
        $response = $this->client->request('POST', '/api/status', ['json' => []]);
        return $response->getBody();
    }

    public function statusList($status) {
        // List of logged users with this status
        $response = $this->client->request('POST', '/api/status_list', ['json' => ['status' => $status]]);
        return $response->getBody();
    }

    public function statusListHost($status) {
        // List of users logged in host with their statuses
        $response = $this->client->request('POST', '/api/status_list_host', ['json' => ['host' => $this->config['domain'], 'status' => $status]]);
        return $response->getBody();
    }

    public function statusNum($status) {
        // Number of logged users with this status
        $response = $this->client->request('POST', '/api/status_num', ['json' => ['status' => $status]]);
        return $response->getBody();
    }

    public function statusNumHost($status) {
        // Number of logged users with this status in host
        $response = $this->client->request('POST', '/ap/status_num_host', ['json' => ['host' => $this->config['domain'], 'status' => $status]]);
        return $response->getBody();
    }

    public function stop() {
        // Stop ejabberd gracefully
        $response = $this->client->request('POST', '/api/stop', ['json' => []]);
        return $response->getBody();
    }

    public function stopKindly($delay, $announcement) {
        // Inform users and rooms, wait, and stop the server
        // Provide the delay in seconds, and the announcement quoted, for example: ejabberdctl stop_kindly 60 "The server will stop in one minute."
        $response = $this->client->request('POST', '/api/stop_kindly', ['json' => ['delay' => $delay, 'announcement' => $announcement]]);
        return $response->getBody();
    }

    public function subscribeRoom($user, $nick, $room, $nodes) {
        // Subscribe to a MUC conference
        $response = $this->client->request('POST', '/api/subscribe_room', ['json' => ['user' => $user, 'nick' => $nick, 'room' => $room, 'nodes' => $nodes]]);
        return $response->getBody();
    }

    public function unregister($user) {
        // Unregister a user
        $response = $this->client->request('POST', '/api/unregister', ['json' => ['user' => $user, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

    public function unsubscribeRoom($user, $room) {
        // Unsubscribe from a MUC conference
        $response = $this->client->request('POST', '/api/unsubscribe_room', ['json' => ['user' => $user, 'room' => $room]]);
        return $response->getBody();
    }

    public function update($module) {
        //Update the given module, or use the keyword: all
        $response = $this->client->request('POST', '/api/update', ['json' => ['module' => $module]]);
        return $response->getBody();
    }

    public function updateList() {
        // List modified modules that can be updated
        $response = $this->client->request('POST', '/api/update_list', ['json' => []]);
        return $response->getBody();
    }

    public function userResources() {
        // List user’s connected resources
        $response = $this->client->request('POST', '/api/user_resources', ['json' => []]);
        return $response->getBody();
    }

    public function userSessionsInfo($user) {
        // Get information about all sessions of a user
        $response = $this->client->request('POST', '/api/user_sessions_info', ['json' => ['user' => $user, 'host' => $this->config['domain']]]);
        return $response->getBody();
    }

}
