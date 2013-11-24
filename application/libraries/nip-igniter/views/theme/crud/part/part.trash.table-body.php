					<?php foreach($rows as $i => $row):?>
						<tr id="tr-<?php echo $row->{content:primary};?>">
							<td><?php echo ($offset+1)?></td>
							{content:tbody}
							<td><span class="label label-default">Deleted</span></td>
							<td><?php echo date("d M Y", strtotime($row->deleted));?></td>
							<td>
								<button class="btn btn-success btn-xs btnAction" data-id="<?php echo $row->{content:primary};?>" data-url="<?php echo site_url("{$this->controller}/restore");?>">Restore</button>
								<button class="btn btn-danger btn-xs btnAction" data-id="<?php echo $row->{content:primary};?>" data-url="<?php echo site_url("{$this->controller}/force-delete");?>">Delete</button>
							</td>
						</tr>
					<?php $offset++;endforeach;?>