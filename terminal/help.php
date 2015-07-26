<div class="help-row"><span class="help-namespace">namespace </span>&gt;<span class="help-command">command1 command2 ... </span>:<span class="help-param">param1,param2,...</span> - <span class="help-description">Execute command or command combination with parameters from namespace</span></div>
<div class="help-row"><span class="help-command">clear</span> - <span class="help-description">global command for log clear</span></div>
<div class="help-row"><span class="help-command">help</span> - <span class="help-description">global command to get help</span></div>
<div class="help-row"><span class="help-chapter"> - Namespaces:</span></div>
<div class="help-row"><span class="help-namespace">stack</span> - <span class="help-description">commands for PHP stack</span></div>
<div class="help-row"><span class="help-namespace">table</span> - <span class="help-description">commands for calatog of production</span></div>
<div class="help-row"><span class="help-namespace">system</span> - <span class="help-description">system commands</span></div>

<div class="help-row"><span class="help-namespace">stack</span>&gt;
    <span class="help-command">create </span><span class="help-command">stack_name</span>:
    <span class="help-param">el1,el2,...</span>
    <span class="help-description">Create stack_name with elements el1,el2,...</span></div>
<div class="help-row"><span class="help-namespace">stack</span>&gt;
    <span class="help-command">push</span><span class="help-command"> stack_name</span>:
    <span class="help-param">el</span>
    <span class="help-description">Push into stack_name element el</span></div>
<div class="help-row"><span class="help-namespace">stack</span>&gt;
    <span class="help-command">pop</span><span class="help-command"> stack_name</span>
    <span class="help-description">Pop element from stack_name</span></div>
<br>
<div class="help-row"><span class="help-namespace">table</span>&gt;
    <span class="help-command">select</span>:
    <span class="help-param">feld_name ASC/DESC</span>
    <span class="help-description">Select table ordered by field_name to ASC or DESC</span></div>    
<div class="help-row"><span class="help-namespace">table</span>&gt;
    <span class="help-command">login</span> <span class="help-command">username</span>:
    <span class="help-param">password</span>
    <span class="help-description">Login user for table management</span></div>
<div class="help-row"><span class="help-namespace">table</span>&gt;
    <span class="help-command">logout</span>
    <span class="help-description">Logout user</span></div>
<div class="help-row"><span class="help-namespace">table</span>&gt;
    <span class="help-command">add</span> <span class="help-command">row/user</span>:
    <span class="help-param">data</span>
    <span class="help-description">Add row or user</span></div>
<div class="help-row"><span class="help-namespace">table</span>&gt;
    <span class="help-command">edit</span>:
    <span class="help-param">col1,col2,...</span>
    <span class="help-description">Open cols to edit</span></div>
<div class="help-row"><span class="help-namespace">table</span>&gt;
    <span class="help-command">save</span>
    <span class="help-description">Save changes</span></div>