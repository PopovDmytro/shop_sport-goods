<div style="display: none;" id="items">{$items}</div>
<div style="display: none;" id="check">{$check}</div>
{if $view}
    <table class="testTable" cellpadding="0" cellspacing="0">
        {if $view eq 'errors'}
            {foreach from=$result item=controller key=key_controller}
                <tr class="trController">
                    <td colspan="4">
                        {if $hosting and $hosting eq 'yes' and $key_controller eq $hosting_name}
                            Hosting Tests
                        {else}
                            {$key_controller}
                        {/if}
                    </td>
                </tr>
                
                {foreach from=$controller item=method key=key_method}
                    {if $method.errors}
                        {if !$hosting or $hosting neq 'yes' or $key_controller neq $hosting_name}
                            <tr class="trMethod">
                                <td colspan="3">
                                    {$key_method}
                                </td>
                                <td class="methodTime" width="100px">{$method.time}</td>
                            </tr>
                        {/if}
                    {/if}
                    
                    {foreach from=$method.errors item=error}
                        <tr class="trError {if $error.time > 6}redMethod{/if}">
                            <td colspan="2">
                                {$error.message}
                            </td>
                            <td class="methodTime" width="100px">{$error.time}</td>
                            <td class="methodTime" width="100px">{$error.error_line}</td>
                        </tr>
                    {/foreach}
                {/foreach}
            {/foreach}
        {elseif $view eq 'passed'}
            {foreach from=$result item=controller key=key_controller}
                <tr class="trController">
                    <td colspan="4">
                        {if $hosting and $hosting eq 'yes' and $key_controller eq $hosting_name}
                            Hosting Tests
                        {else}
                            {$key_controller}
                        {/if}
                    </td>
                </tr>
                
                {foreach from=$controller item=method key=key_method}
                    {if $method.passed}
                        {if !$hosting or $hosting neq 'yes' or $key_controller neq $hosting_name}
                            <tr class="trMethod">
                                <td colspan="3">
                                    {$key_method}
                                </td>
                                <td class="methodTime" width="100px">{$method.time}</td>
                            </tr>
                        {/if}
                    {/if}
                    
                    {foreach from=$method.passed item=passed}
                        <tr class="trPassed {if $passed.time > 6}redMethod{/if}">
                            <td>{$passed.message}</td>
                            <td width="100px">{$passed.result}</td>
                            <td class="methodTime" width="100px">{$passed.time}</td>
                            <td class="methodTime" width="100px">{$passed.error_line}</td>
                        </tr>
                    {/foreach}
                {/foreach}
            {/foreach}
        {else}
            {foreach from=$result item=controller key=key_controller}
                <tr class="trController">
                    <td colspan="4">
                        {if $hosting and $hosting eq 'yes' and $key_controller eq $hosting_name}
                            Hosting Tests
                        {else}
                            {$key_controller}
                        {/if}
                    </td>
                </tr>
                
                {foreach from=$controller item=method key=key_method}
                    {if !$hosting or $hosting neq 'yes' or $key_controller neq $hosting_name}
                        <tr class="trMethod">
                            <td colspan="3">
                                {$key_method}
                            </td>
                            <td class="methodTime" width="100px">{$method.time}</td>
                        </tr>
                    {/if}
                    
                    {foreach from=$method.errors item=error}
                        <tr class="trError {if $error.time > 6}redMethod{/if}">
                            <td colspan="2">
                                {$error.message}
                            </td>
                            <td class="methodTime" width="100px">{$error.time}</td>
                            <td class="methodTime" width="100px">{$error.error_line}</td>
                        </tr>
                    {/foreach}
                    
                    {foreach from=$method.passed item=passed}
                        <tr class="trPassed {if $passed.time > 6}redMethod{/if}">
                            <td>{$passed.message}</td>
                            <td width="100px">{$passed.result}</td>
                            <td class="methodTime" width="100px">{$passed.time}</td>
                            <td class="methodTime" width="100px">{$passed.error_line}</td>
                        </tr>
                    {/foreach}
                {/foreach}
            {/foreach}
        {/if}
    </table>
{/if}