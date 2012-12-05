<div class="index">
<b>Table of contents</b><br />
1. <?php echo $this->Html->link(__('General Layout', true), array('controller' => 'pages', 'action' => 'display', 'documentation')); ?><br />
2. <?php echo $this->Html->link(__('User Management and Global Actions', true), array('controller' => 'pages', 'action' => 'display', 'user_management')); ?><br />
3. <?php echo $this->Html->link(__('Using the system', true), array('controller' => 'pages', 'action' => 'display', 'using_the_system')); ?><br />
4. <?php echo $this->Html->link(__('Administration', true), array('controller' => 'pages', 'action' => 'display', 'administration')); ?><br />
<ul>
	<li>a. <a href="#regex">Import Whitelist</a></li>
	<li>b. <a href="#whitelist">Signature Whitelist</a></li>
	<li>c. <a href="#user">User Management</a></li>
	<li>d. <a href="#roles">Role Management</a></li>
	<li>e. <a href="#logs">Logging</a></li>
</ul>
5. <?php echo $this->Html->link(__('Categories and Types', true), array('controller' => 'pages', 'action' => 'display', 'categories_and_types')); ?>
<br /><br /><hr /><br />
<h2><a name ="regex"></a>Import Whitelist</h2>
The system allows administrators to set up rules for regular expressions that will automatically altered newly entered or imported events (from GFI Sandbox).<br /><br />
<h3>The purpose of Import Whitelist entries</h3>
They can be used for several things, such as unifying the capitalisation of file paths for more accurate event correlation or to automatically censor the usernames and standardise the file paths (changing C:\Users\UserName\Appdata\Roaming\file.exe to %APPDATA%\Roaming\file.exe).<br />
The second use is blocking, if just the regex is given and no replacement, any event or attribute containing info or value conform the regex will not be added.<br /><br />
<h3>Adding and modifying entries</h3>
Administrators can add, edit or delete Import Whitelist rules, which are made up of a regex pattern that the system searches for and a replacement for the detected pattern.<br /><br />
<p><img src="/img/doc/regex.png" alt = "" title = "Add, edit or remove Regex entries that will affect all newly created attributes here."/></p><br />
<br /><hr /><br />
<h2><a name ="whitelist"></a>Managing the Signature whitelist</h2>
The signature whitelist view, accessible through the administration menu on the left, allows administrators to create and maintain a list of addresses that are whitelisted from ever being added to the NIDS signatures. Addresses listed here will be commented out when exporting the NIDS list.<br /><br />
<h3>Whitelisting an address:</h3>
	While in the whitelist view, click on New Whitelist on the left to bring up the add whitelist view to add a new address. <br /><br />
<h3>Managing the list:</h3>
	When viewing the list of whitelisted addresses, the following pieces of information are shown: The ID of the whitelist entry (assigned automatically when a new address is added), the address itself that is being whitelisted and a set of controls allowing you to delete the entry or edit the address.<br /><br />
<img src="/img/doc/whitelist.png" alt = "Whitelist" title = "You can edit or delete currently white-listed addresses using the action buttons on this list."/><br /><br />
<br /><hr /><br />
<h2><a name ="user"></a>Managing the users:</h2>
As an admin, you can set up new accounts for users, edit the profiles of users, delete them, or just have a look at all the viewers' profiles.<br /><br />
<img src="/img/doc/add_user.png" alt = "Add user" style="float:right;" title = "Fill this form out to add a new user. Keep in mind that the drop-down menu titled Group controls the privileges the user will have."/>
<h3>Adding a new user:</h3>
To add a new user, click on the New User button in the administration menu to the left and fill out the following fields in the view that is loaded:<br /><br />
<ul>
	<li><em>Email:</em> The user's e-mail address, this will be used as his/her login name and as an address to send all the automatic e-mails and e-mails sent by contacting the user as the reporter of an event.<br /><br /></li>
	<li><em>Password:</em> A temporary password for the user that he/she should change after the first login. Make sure that it is at least 6 characters long, includes a digit or a special character and contains at least one upper-case and at least one lower-case character.<br /><br /></li>
	<li><em>Confirm Password:</em> This should be an exact copy of the Password field.<br /><br /></li>
	<li><em>Org:</em>The organisation of the user. Entering ADMIN into this field will give administrator privileges to the user.<br /><br /></li>
	<li><em>Roles:</em> A drop-down list allows you to choose a role-roup that the user should belong to. Roles define the privileges of the user. To learn more about roles, <a href=#roles>click here</a>.<br /><br /></li>
	<li><em>Authkey:</em> This is assigned automatically and is the unique authentication key of the user (he/she will be able to reset this and receive a new key). It is used for exports and for connecting one server to another (as described in section xxyyzz).<br /><br /></li>
	<li><em>NIDS Sid:</em> Nids ID, not yet implemented.<br /><br /></li>
	<li><em>Termsaccepted:</em> Indicates whether the user has accepted the terms of use already or not.<br /><br /></li>
	<li><em>Gpgkey:</em> The key used for encrypting e-mails sent through the system. <br /><br /></li>
</ul>
<h3>Listing all users:</h3>
To list all current users of the system, just click on List Users under the administration menu to the left. A view will be loaded with a list of all users and the following columns of information:<br /><br />
<img src="/img/doc/list_users.png" alt = "List users" title = "View, Edit or Delete a user using the action buttons to the right."/><br /><br />
<ul>
	<li><em>Id:</em> The user's automatically assigned ID number.<br /><br /></li>
	<li><em>Org:</em> The organisation that the user belongs to.<br /><br /></li>
	<li><em>Email:</em> The e-mail address (and login name) of the user.<br /><br /></li>
	<li><em>Autoalert:</em> Shows whether the user has auto-alerts enabled and is always receiving the mass-emails that he is eligible for.<br /><br /></li>
	<li><em>Gpgkey:</em> Shows whether the user has entered a Gpgkey yet.<br /><br /></li>
	<li><em>Nids Sid:</em> Shows the currently assigned NIDS ID.<br /><br /></li>
	<li><em>Termsaccepted:</em> This flag indicates whether the user has accepted the terms of use or not.<br /><br /></li>
	<li><em>Newsread:</em> The last point in time when the user has looked at the news section of the system.<br /><br /></li>
	<li><em>Action Buttons:</em> Here you can view a detailed view of a user, edit the basic details of a user (same view as the one used for creating a new user, but all the fields come filled out by default) or remove a user completely. <br /><br /></li>
</ul>
<br /><hr /><br />
<h2><a name ="roles"></a>Managing the roles</h2>
Privileges are assigned to users by assigning them to role-groups, which use one of four options to determine what the users belonging to them are able to do on the site. The four options are: Read Only, Manage My Own Events, Manage Organisation Events, Manage &amp; Publish Organisation Events. <br /><br />
<em>Read Only:</em> This allows the user to browse events that his organisation has access to, but doesn't allow any changes to be made to the database. <br /><br />
<em>Manage My Own Events:</em> The second option, gives its users rights to create, modify or delete their own events, but they cannot publish them. <br /><br />
<em>Manage Organization Events:</em> allows users to create events or modify and delete events created by a member of their organisation. <br /><br />
<em>Manage &amp; Publish Organisation Events:</em> This last setting, gives users the right to do all of the above and also to publish the events of their organisation.<br /><br />
<h3>Creating groups:</h3>
When creating a new group, you will have to enter a name for the group to be created and set up the permissions (as described above) using four check-boxes, one for each permission flag.<br /><br />
<h3>Listing groups:</h3>
By clicking on the List Groups button, you can view a list of all the currently registered groups and a list of the permission flags turned on for each. In addition, you can find buttons that allow you to edit and delete the groups. Keep in mind that you will need to first remove every member from a group before you can delete it.<br /><br />
<img src="/img/doc/list_groups.png" alt = "List groups" title = "You can View, Edit or Delete groups using the action buttons to the right in each row. Keep in mind that a group has to be devoid of members before it can be deleted."/><br /><br />
<br /><hr /><br />
<h2><a name ="logs"></a>Using the logs of MISP</h2>
Admins are able to browse or search the logs that MISP automatically appends each time any action is taken that alters the data contained within the system (or if a user logs in and out).<br /><br />
<h3>Browsing the logs:</h3>
Listing all the log entries will show the following columns:<br /><br />
<img src="/img/doc/list_logs.png" alt = "List logs" title = "Here you can view a list of all logged actions."/><br /><br />
<ul>
	<li><em>Id:</em> The automatically assigned ID number of the entry.<br /><br /></li>
	<li><em>Email:</em> The e-mail address of the user whose actions triggered the entry.<br /><br /></li>
	<li><em>Org:</em> The organisation of the above mentioned user.<br /><br /></li>
	<li><em>Created:</em> The date and time when the entry originated.<br /><br /></li>
	<li><em>Action:</em> The action's type. This can include: login/logout for users, add, edit, delete for events, attributes, users and servers.<br /><br /></li>
	<li><em>Title:</em> The title of an event always includes the target type (Event, User, Attribute, Server), the target's ID and the target's name (for example: e-mail address for users, event description for events).<br /><br /></li>
	<li><em>Change:</em> This field is only filled out for entries with the action being add or edit. The changes are detailed in the following format:<br />
			<i>variable (initial_value)</i> =&gt; <i>(new_value)</i>,...<br />
			When the entry is about the creation of a new item (such as adding a new event) then the change will look like this for example:<br />
			<i>org()</i> =&gt; <i>(ADMIN)</i>, <i>date()</i> =&gt; <i>(20012-10-19)</i>,... <br /><br />
</ul>
<img src="/img/doc/search_log.png" alt = "Search log" style="float:right;" title = "You can search the logs using this form, narrow down your search by filling out several fields."/>
<h3>Searching the Logs:</h3>
Another way to browse the logs is to search it by filtering the results according to the following fields (the search is a sub-string search, the sub-string has to be an exact match for the entry in the field that is being searched for):<br /><br />
<ul>
	<li><em>Email:</em> By searching by Email, it is possible to view the log entries of a single user.<br /><br /></li>
	<li><em>Org:</em> Searching for an organisation allows you to see all actions taken by any member of the organisation.<br /><br /></li>
	<li><em>Action:</em> With the help of this drop down menu, you can search for various types of actions taken (such as logins, deletions, etc).<br /><br /></li>
	<li><em>Title:</em> There are several ways in which to use this field, since the title fields contain several bits of information and the search searches for any substrings contained within the field, it is possible to just search for the ID number of a logged event, the username / server's name / event's name / attribute's name of the event target.<br /><br /></li>
	<li><em>Change:</em> With the help of this field, you can search for various specific changes or changes to certain variables (such as published will find all the log entries where an event has gotten published, ip-src will find all attributes where a source IP address has been entered / edited, etc).<br /><br /></li>
</ul>

</div>
<div class="actions">
	<ul>
		<?php echo $this->element('actions_menu'); ?>
	</ul>
</div>