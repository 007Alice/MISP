<div class="event index">
<h2>Export</h2>
<p>To make exports available for automated tools an authentication key is used. This makes it easier for your tools to access the data without further form-based-authentiation.<br/>
<strong>Make sure you keep that key secret as it gives access to the entire database !</strong></p>
<p>Your current key is: <code><?php echo $me['authkey'];?></code>.
You can <?php echo $this->Html->link('reset', array('controller' => 'users', 'action' => 'resetauthkey', 'me'));?> this key.
</p>

<h3>XML Export</h3>
<p>An automatic export of all events and attributes <small>(except file attachments)</small> is available under a custom XML format.</p>
<p>You can configure your tools to automatically download the following file:</p>
<pre><?php echo Configure::read('CyDefSIG.baseurl');?>/events/xml/<?php echo $me['authkey']; ?></pre>
<p>If you only want to fetch a specific event append the eventid number:</p>
<pre><?php echo Configure::read('CyDefSIG.baseurl');?>/events/xml/<?php echo $me['authkey']; ?>/1</pre>
<p>Also check out the <?php echo $this->Html->link(__('User Guide', true), array('controller' => 'pages', 'action' => 'display', 'documentation')); ?> to read about the REST API.</p>
<p></p>

<h3>NIDS Export</h3>
<p>An automatic export of all network related attributes is available under the Snort rule format. Only <em>published</em> events and attributes marked as <em>IDS Signature</em> are exported.</p>
<p>You can configure your tools to automatically download the following file:</p>
<pre><?php echo Configure::read('CyDefSIG.baseurl');?>/events/nids/<?php echo $me['authkey']; ?></pre>
<p></p>
<p>Administration is able to maintain a whitelist containing host, domain name and IP numbers to exclude from the NIDS export.</p>

<h3>HIDS Export</h3>
<p>An automatic export of all host related attributes is available, containing MD5 checksums. Only <em>published</em> events and attributes marked as <em>IDS Signature</em> are exported.</p>
<p>You can configure your tools to automatically download the following files:</p>
<h4>md5</h4>
<pre><?php echo Configure::read('CyDefSIG.baseurl');?>/events/hids_md5/<?php echo $me['authkey']; ?></pre>
<h4>sha1</h4>
<pre><?php echo Configure::read('CyDefSIG.baseurl');?>/events/hids_sha1/<?php echo $me['authkey']; ?></pre>
<p></p>

<h3>Text Export</h3>
<p>An automatic export of all attributes of a specific type to a plain text file.</p>
<p>You can configure your tools to automatically download the following files:</p>
<pre>
<?php foreach ($sig_types as $sig_type):?>
<?php echo Configure::read('CyDefSIG.baseurl');?>/events/text/<?php echo $me['authkey']; ?>/<?php echo $sig_type . "\n";?>
<?php endforeach;?>
</pre>
<p></p>

<h3>Saved search XML Export</h3>
<p>We plan to make it possible to export data using searchpatterns.<br/>
This would enable you to export:</p>
<ul>
<li>only your own attributes</li>
<li>date ranges</li>
<li>only specific attribute types (domain)</li>
<li>...</li>
</ul>



</div>
<div class="actions">
	<ul>
		<?php echo $this->element('actions_menu'); ?>
	</ul>
</div>