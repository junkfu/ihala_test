<div class="container">
    <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="">iHala後台</a>
            </div>
            <div class="collapse navbar-collapse in">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="<?php echo env('APP_URL').'cust_list'; ?>">客戶列表</a></li>
                    <li><a href="<?php echo env('APP_URL').'action_list'; ?>">資料傳輸列表</a></li>
                </ul>
                <!--
                <ul class="nav navbar-nav navbar-right">  
                    <li><a href="">登出</a></li>
                </ul>
                -->
            </div>
        </div>
    </div>
</div>
