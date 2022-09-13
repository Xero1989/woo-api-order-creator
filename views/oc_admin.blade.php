<div style="padding-right: 2%;">

    <form id="oc_form_admin">

        <h1>Settings</h1>

        <table class="form-table">
            <tr>
                <th>
                    Enable
                </th>
                <td>
                    @if($oc_enable)
                    <input type="checkbox" id="oc_enable" checked />
                    @else
                    <input type="checkbox" id="oc_enable" />
                    @endif
                </td>
            </tr>
            <tr>
                <th>
                    <label>Description</label>
                </th>
                <td>
                    <textarea id="oc_description">{{$oc_description}}</textarea>
                </td>
            </tr>
            <tr>
                <th>
                    <label>Webshop ID</label>
                </th>
                <td>
                    <input type="text" value="{{$oc_webshop_id}}" id="oc_webshop_id" />
                </td>
            </tr>
        </table>
        <br>

        <div id="ajax_response_message"></div>

        <input type="button" onclick="javascript:oc_save_settings()" class="button button-primary bt_save_settings" value="Save Settings">

    </form>

</div>