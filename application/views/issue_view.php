<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测管理 <span>任务详情</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/tice/task_list">提测管理</a></li>
          <li class="active">任务详情</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
                    
      <div class="panel">
          <div class="panel-heading">
              <h5 class="bug-key-title">ISSUE-<?php echo $row['id'];?></h5>
              <div class="panel-title"><?php echo $row['issue_name'];?></div>
          </div><!-- panel-heading-->
          <div class="panel-body">
              <div class="btn-group mr10">
                  <button class="btn btn-primary" type="button"><i class="fa fa-pencil mr5"></i> 编辑</button>
                  <button class="btn btn-primary" type="button"><i class="fa fa-comments mr5"></i> 提交代码</button>
                  <button class="btn btn-primary" type="button"><i class="fa fa-trash-o mr5"></i> 删除</button>
              </div>
              
              <div class="btn-group mr10">
                  <button class="btn btn-default" type="button">解决</button>
                  <button class="btn btn-default" type="button">关闭</button>
              </div>

              <br /><br />
              
              <div class="row">
                  <div class="col-sm-12">
                      <h5 class="subtitle subtitle-lined">Details</h5>
                      <div class="row">
                          <div class="col-sm-6">
                              <div class="row">
                                  <div class="col-xs-6">类型</div>
                                  <div class="col-xs-6">Bug</div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-6">阶段</div>
                                  <div class="col-xs-6">Major</div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-6">标签</div>
                                  <div class="col-xs-6"><a href="">企业库</a> <a href="">联盟广告</a></div>
                              </div>
                          </div><!-- col-sm-6 -->
                          <div class="col-sm-6">
                              <div class="row">
                                  <div class="col-xs-6">状态</div>
                                  <div class="col-xs-6">进行中...</div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-6">版本</div>
                                  <div class="col-xs-6">4.1, 4.2</div>
                              </div>
                              <div class="row">
                                  <div class="col-xs-6">提交人</div>
                                  <div class="col-xs-6"><a href="">李齐明</a></div>
                              </div>
                          </div><!-- col-sm-6 -->
                      </div><!-- row -->
                      
                      <br /><br />
                      
                      <h5 class="subtitle subtitle-lined">描述</h5>
                      <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
                      <p>Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
                      
                      <br /><br />
                      
                      <ul class="nav nav-tabs">
                          <li class="active"><a data-toggle="tab" href="#comments"><strong>Comments</strong></a></li>
                      </ul>
                      <br />
                      <div class="tab-content noshadow">
                          <div id="comments" class="tab-pane active">
                              <ul class="media-list comment-list">
                                  
                                  <li class="media">
                                      <a href="#" class="pull-left">
                                          <img alt="" src="images/photos/user1.png" class="media-object">
                                      </a>
                                      <div class="media-body">
                                          <a class="btn btn-default btn-xs pull-right reply" href=""><i class="fa fa-reply"></i></a>
                                          <h4>Nusja Nawancali</h4>
                                          <small class="text-muted">January 10, 2014 at 7:30am</small>
                                          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                      </div>
                                  </li><!-- media -->
                      
                              </ul>
                              <br />
                              <button class="btn btn-primary"><i class="fa fa-comments mr5"></i>提交代码</button>
                          </div><!-- tab-pane -->
                      </div><!-- tab-content -->
                      
                  </div>
              </div><!-- row -->
              
          </div><!-- panel-body -->
      </div><!-- panel -->
      
  </div><!-- contentpanel -->
    
  </div><!-- mainpanel -->
  
</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/retina.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>
<script>
  $(document).ready(function(){
    $(".delete-row").click(function(){
      var c = confirm("确认要删除吗？");
      if(c) {
        id = $(this).attr("reposid");
        $.ajax({
          type: "GET",
          url: "/conf/task_del/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              $("#tr-"+id).fadeOut(function(){
                $("#tr-"+id).remove();
              });
              return false;
            } else {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-danger',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
              setTimeout(function(){
                location.href = data.url;
              }, 2000);
            };
          }
        });
      }
    });

    // Show aciton upon row hover
    jQuery('.table-responsive tbody tr').hover(function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 0});
    });

  });
</script>

</body>
</html>
