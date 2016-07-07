{if $action eq 1 or $action eq 2 or $action eq 8}
   {include file="CRM/Verkiezingslijst/Form/Verkiezingslijst.tpl"}
{else}

{if $rows}
    <div class="view-content">
    {if $action ne 1 and $action ne 2}
        <div class="action-link">
        <a href="{crmURL q="action=add&reset=1&cid=`$cid`"}" id="newPositie" class="button"><span><div class="icon add-icon"></div>{ts}Voeg nieuwe kandidaat toe{/ts}</span></a>
        </div>
    {/if}


    <div id="ltype">
    {strip}
        <br/>
        <table class="selector">        
            <tr class="columnheader">
                <th >{ts}Verkiezing{/ts}</th>
                <th >{ts}Positie{/ts}</th>
                <th >{ts}Kandidaat{/ts}</th>
                <th >{ts}Action{/ts}</th>
            </tr>
            {foreach from=$rows item=row}
                <tr id="row_{$row.id}" class="crm-odoo_contribution_setting {cycle values="odd-row,even-row"} {$row.class}">
                    <td class="crm-verkiezingslijst-verkiezing">{$row.verkiezing}</td>
                    <td class="crm-verkiezingslijst-positie">{$row.positie}</td>
                    <td class="crm-verkiezingslijst-kandidaat"><a href="{$row.kandidaat_url}">{$row.kandidaat_display_name}</a></td>
                    <td>{$row.action|replace:'xx':$row.id}</td>
                </tr>
            {/foreach}
        </table>
    {/strip}
    </div>
    </div>
{elseif $action ne 1}
    <div class="messages status no-popup">
      <div class="icon inform-icon"></div>
        {ts}Er zijn geen verkiezingslijsten{/ts}
     </div>
    <div class="action-link">
        <a href="{crmURL q="action=add&reset=1&cid=`$cid`"}" id="newPositie" class="button"><span><div class="icon add-icon"></div>{ts}Voeg nieuwe kandidaat toe{/ts}</span></a>
    </div>
{/if}
{/if}