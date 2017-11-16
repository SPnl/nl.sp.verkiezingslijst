<h3>{if $action eq 1}{ts}Nieuwe kandidaat{/ts}{elseif $action eq 2}{ts}Bewerk kandidaat{/ts}{else}{ts}Verwijder kandidaat{/ts}{/if}</h3>
{* HEADER *}
<div class="crm-block crm-form-block">
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="top"}
</div>

{if $action eq 8}
  <div class="messages status no-popup">  
      <div class="icon inform-icon"></div>{ts}Do you want to continue?{/ts}
  </div>
{else}

    <div class="crm-section">
        <div class="label">{$form.kandidaat_ids.label}</div>
        <div class="content">{$form.kandidaat_ids.html}</div>
        <div class="clear"></div>
    </div>

    <div class="crm-section">
        <div class="label">{$form.verkiezing.label}</div>
        <div class="content">{$form.verkiezing.html}</div>
        <div class="clear"></div>
    </div>

    <div class="crm-section">
        <div class="label">{$form.positie.label}</div>
        <div class="content">{$form.positie.html}</div>
        <div class="clear"></div>
    </div>

    <div class="crm-section">
        <div class="label">{$form.afdracht_verklaring_ondertekend.label}</div>
        <div class="content">{$form.afdracht_verklaring_ondertekend.html}</div>
        <div class="clear"></div>
    </div>

    <div class="crm-section">
        <div class="label">{$form.gekozen.label}</div>
        <div class="content">{$form.gekozen.html}</div>
        <div class="clear"></div>
    </div>
{/if}
{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>

</div>
