<h5 class="subtitle">快捷方式</h5>
<ul class="nav nav-pills nav-stacked nav-email">
	<li<?php if ($this->uri->segment(2, 'index') == 'index') {?> class="active"<?php } ?>><a href="/analytics"><i class="fa fa-square<?php if ($this->uri->segment(2, 'index') != 'index') echo '-o'; ?>"></i> 计划列表</a></li>
	<li<?php if ($this->uri->segment(2, 'index') == 'issue') {?> class="active"<?php } ?>><a href="/analytics/issue"><i class="fa fa-square<?php if ($this->uri->segment(2, 'index') != 'issue') echo '-o'; ?>"></i> 任务列表</a></li>
	<li<?php if ($this->uri->segment(2, 'index') == 'bug') {?> class="active"<?php } ?>><a href="/analytics/bug"><i class="fa fa-square<?php if ($this->uri->segment(2, 'index') != 'bug') echo '-o'; ?>"></i> BUG列表</a></li>
</ul>