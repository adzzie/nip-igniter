					<?php foreach($rows as $i => $row):?>
						<tr id="tr-<?php echo $row->{content:primary};?>">
							<td><?php echo ($offset+1)?></td>
							{content:tbody}
							<td>
								<?php if($row->updated):?>
									<span class="label label-default">Updated</span></td>
								<?php else:?>
									<span class="label label-default">Created</span></td>
								<?php endif;?>
							<td>
								<?php if($row->updated):?>
									<?php echo date("d M Y", strtotime($row->updated));?>
								<?php else:?>
									<?php echo date("d M Y", strtotime($row->created));?>
								<?php endif;?>
							</td>
							<td>
								<button class="btn btn-info btn-xs btnShowModal" data-id="<?php echo $row->{content:primary};?>" data-url="<?php echo site_url("{$this->controller}/view");?>">View</button>
								<button class="btn btn-info btn-xs btnShowModal" data-id="<?php echo $row->{content:primary};?>" data-url="<?php echo site_url("{$this->controller}/edit");?>">Edit</button>
								<button class="btn btn-danger btn-xs btnDelete" data-id="<?php echo $row->{content:primary};?>" data-url="<?php echo site_url("{$this->controller}/delete");?>">Delete</button>
							</td>
						</tr>
					<?php $offset++;endforeach;?>