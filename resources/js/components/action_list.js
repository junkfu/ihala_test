window.onload = function(){
    var app = new Vue({
        el: '#app'
    });    
}

Vue.component('action_list', {
    template: '#action_list',   

    data: function(){
        return {
            pages:0,
            current_page: 0,
            actions: [],
            consultants:[],
            crmid_selected:'',
			//0 update 1 insert
            //modal_status: 0,
            modal: {},
            crm_data:{},
            action_data:{},
			filter:[]
        }
    },
    mounted: function(){
        this.getActionsPages();
        this.getActionsByPage(1);
    },
    watch:{
       
    },
    computed:{
        page_start: function(){
            return Math.floor((this.current_page - 1) / 10) * 10;
        }
    },
    methods: { 
        test:function(){
            alert('123');
            console.log('111');
        },
        getActionsPages: function(){

            axios({
                method: 'get',
				url: '/action/pages',               
            }).then((response) => {
                this.pages = response.data;
            }).catch(error => {
                console.log(error);
                alert(error);
            }); 

        },
        getActionsByPage: function(page){
            this.current_page = page;
            axios({
                method: 'get',
				url: '/action/page/' + page,               
            }).then((response) => {
                //console.log(response.data);
                this.actions = response.data[0]; //console.log('actions', this.actions);
            }).catch(error => {
                console.log(error);
                alert(error);
            })
          
        },

		searchActions:function(){
			console.log(this.selected);
			//return;
			
			
			let params = new URLSearchParams();
			params.append('ichk', this.$refs.csrf.value);
			params.append('conditions',this.filter);
			params.append('attending_date',this.selected);
			axios({
                method: 'post',
				url: '/chinaseminar/api/activity/searchActions', 
                data: params,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                }                
            })	
            .then((response) => {
                console.log(response.data);
                //this.consultants = response.data;
				this.actions = response.data;
				this.pages =0;
				this.current_page=0
            }); 
        },
        updateModal:function(action){
            var crmIds = JSON.parse(action.response_obj);
            var idList = new Array();
            for(var i in crmIds){
                idList.push(crmIds[i]);
            }

            var data ={};
            data.idList = idList;
			axios({
                method: 'post',
				url: 'http://192.168.20.65:9694/api/v1.0/suite-crm/get-contact-list-by-id-list', 
                data: data,
                headers:{
                    'Content-Type': 'application/json',
                }                
            })	
            .then((response) => {
                //console.log(response.data.dataList);
                var dataList = response.data.dataList;
                //console.log(action.data);
                var action_data = JSON.parse(action.data);
                //console.dir(action_data);
                var crmData= new Array();
                //console.dir(dataList);
                for(var i=0;i<dataList.length;i++){
                    var row = {};
                    for(var key in action_data){
                        row[key] = dataList[i]['attributes'][key];
                    }
                    row['id'] = dataList[i]['attributes']['id'];
                    crmData.push(row);
                }

                
                //console.log('-----action-----');
                //console.dir(dataList);
                let data = JSON.parse(action.data) ;
                let display_data ={};
                for(var key in data){
                    //console.log(key'key...'+key+'...'+data[key]);
                    //若是表單資料為空或未定義,則不顯示輸入框
                    if(data[key]==null || data[key]=='undefined'){

                    }else{
                        //console.log('in');
                        display_data[key] = data[key];
                    }
                }
                //console.dir(display_data);
                //console.dir(crmData);
                this.action_data = _.cloneDeep(display_data);
                this.crm_data = _.cloneDeep(crmData);

            }); 

        },
        updateDataToCrm:function(cust_data){
            console.dir(cust_data);
            //console.log(this.crmid_selected);

            //var ele = document.getElementById('actionModal');
            //console.dir(ele);

            if(this.crmid_selected !=0 && typeof(this.crmid_selected)!='undefined'){
                //ele.$emit('hide');
                let data ={};
                data = cust_data;
                data['id'] = this.crmid_selected;
                axios({
                    method: 'post',
                    url: '/action/updateCrmByEmployee', 
                    data: cust_data,
                    headers:{
                        'Content-Type': 'application/json',
                    }                
                })	
                .then((response) => {
                    console.dir(response);
                });
                
            }else{
                alert('請選取CRM ID');
                return;
            }
            
            //if()
        }

        

    }
});