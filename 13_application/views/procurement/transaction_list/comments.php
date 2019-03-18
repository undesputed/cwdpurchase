

						<div class="additional-content">
							<div class="comments">

								<?php foreach($result as $row): ?>
								<div class="comment">
									<div>
										<span class="username"><?php echo $row['emp_fullname']; ?></span>
									</div>
									<div class="comment-box">
										<div class="row">
											<div class="col-xs-8">
												<?php /*echo $row['remarks']; */?>
												<?php if($row['action']!="EDIT" && $row['action']!= "ADD"): ?>
												<?php echo $type; ?> status was changed to <?php echo $this->extra->c_label($row['action']); ?>
												<?php 
														if($row['action']=="REJECTED" || $row['action']=="CANCELLED"){
															if($row['remarks'] !=''){
																echo '<div>" '.$row['remarks'].' "</div>';
															}															
														}
												?>													
												<?php elseif($row['action'] == 'ADD'): ?>
														<?php echo $type; ?> was <?php echo $this->extra->c_label($row['action']); ?>	
												<?php else: ?>
														<?php echo $type; ?> was <?php echo $this->extra->c_label($row['action']); ?>	
												<?php endif; ?>
											</div>	
											<div class="col-xs-4">
												<span class="comment-time"><?php echo date('F d , h:i A',strtotime($row['date_created'])); ?></span>
											</div>	
										</div>										
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>