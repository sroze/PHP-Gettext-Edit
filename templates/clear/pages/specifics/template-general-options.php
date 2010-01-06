				<p><label>Type</label>
					<select name="type" onchange="templateTypeChange(this);">
						<option value="LC_MESSAGES"<?php if ($_POST['type'] == 'LC_MESSAGES') { echo 'selected'; } ?>>LC_MESSAGES</option>
						<option disabled>------------</option>
						<option value="@other@"<?php if ($_POST['type'] == 'LC_MESSAGES') { echo '@other@'; } ?>>Autre</option>
					</select>
					<div class="form_p" id="other_type"<?php if (!empty($_POST['other_type'])) { echo ' style="display: none;"'; } ?>>
						<input type="text" name="other_type" value="" />
					</div>
				</p>