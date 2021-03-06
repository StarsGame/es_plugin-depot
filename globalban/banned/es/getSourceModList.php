<?php
/*
    This file is part of GlobalBan.

    Written by Stefan Jonasson <soynuts@unbuinc.net>
    Copyright 2008 Stefan Jonasson
    
    GlobalBan is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GlobalBan is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GlobalBan.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once(ROOTDIR."/include/database/class.UserQueries.php");
require_once(ROOTDIR."/include/database/class.ServerQueries.php");
require_once(ROOTDIR."/include/database/class.AdminGroupQueries.php");
require_once(ROOTDIR."/include/objects/class.PluginFlag.php");

if($_GET['serverId'] > 0) {
  $serverId = $_GET['serverId'];
}

$userQueries = new UserQueries();
$serverQueries = new ServerQueries();

$server = $serverQueries->getServer($serverId);

$groupId = $server->getGroupId();

// If we have a valid group id, get the admins of that group
if($groupId > 0) {
  $users = $userQueries->getGroupAdmins($groupId); // Admins of a server group
} else {
  $users = $userQueries->getServerAdmins($serverId); // Admins of a server
}

// If the above return no values, that means we should use the users list
if(count($users) < 1) {
  $users = $userQueries->getUsers();
}

foreach($users as $user) {
  if($user->getActive() == 1)
  echo "\"".$user->getSteamId()."\" \"@".$user->getAdminGroupName()."\"\n";
}