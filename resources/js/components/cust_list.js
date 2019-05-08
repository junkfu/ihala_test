window.onload = function(){
    var app = new Vue({
        el: '#app'
    });    
}

Vue.component('cust_list', {
    template: '#cust_list',   

    data: function(){
        return {
            pages:0,
            current_page: 0,
            users: [],
            consultants:[],
			//0 update 1 insert
            modal_status: 0,
            modal: {},
			filter:[]
        }
    },
    mounted: function(){
		
        //this.selected;
        //alert('1234');
        //console.log('999');
        this.getUsersPages();
        this.getUsersByPage(1);   
        //this.getReplyedList();
		//this.getConsultants();		

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
        getUsersPages: function(){

            axios({
                method: 'get',
				url: '/customers/pages',               
            }).then((response) => {
                this.pages = response.data;
            }).catch(error => {
                console.log(error);
                alert(error);
            }); 

        },
        getUsersByPage: function(page){
            this.current_page = page;
            //axios.get('/customers/page/' + page).then(function (response) {
            //    console.log(response.data);
            //    this.users = response.data[0]; //console.log('users', this.users);
            //})
            axios({
                method: 'get',
				url: '/customers/page/' + page,               
            }).then((response) => {
                //console.log(response.data);
                this.users = response.data[0]; //console.log('users', this.users);
            }).catch(error => {
                console.log(error);
                alert(error);
            })
          
        },
		getConsultants: function(){
			
			let params = new URLSearchParams();
			params.append('ichk', this.$refs.csrf.value);
			axios({
                method: 'post',
				url: '/chinaseminar/api/activity/getConsultants', 
                data: params,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                }                
            })	
            .then((response) => {
                console.log(response.data);
                this.consultants = response.data;
            }); 
		},
		searchUsers:function(){
			console.log(this.selected);
			//return;
			
			
			let params = new URLSearchParams();
			params.append('ichk', this.$refs.csrf.value);
			params.append('conditions',this.filter);
			params.append('attending_date',this.selected);
			axios({
                method: 'post',
				url: '/chinaseminar/api/activity/searchUsers', 
                data: params,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                }                
            })	
            .then((response) => {
                console.log(response.data);
                //this.consultants = response.data;
				this.users = response.data;
				this.pages =0;
				this.current_page=0
            }); 
		},
        downloadUsersList: function(){
            //location = '/chinaseminar/api/activity/exportUsers';
			location = '/chinaseminar/api/activity/exportUsers/'+this.selected;
        },
        updateUserModal: function(user){
            //console.log(user);
            this.modal = _.cloneDeep(user);
            this.modal_status = 0;
            
        },
        createUserModal: function(){
            this.modal={};
			this.modal.attending_date=this.selected;
			this.modal_status = 1;
        },
        
        updateUserData: function(data){
            
            let params = new URLSearchParams();
            console.log('edit..');
            console.log(data);
 
            //params.append('ichk', this.$refs.csrf.value);
            params.append('s_id', data.s_id);
            params.append('comm_id', data.comm_id);
            params.append('obj_id', data.obj_id);
            params.append('c_id', data.c_id);
            params.append('cNumber', data.cNumber);
            params.append('name', data.name);
            params.append('origin_name', data.origin_name);
            params.append('sex', data.sex);
            params.append('birth', data.birth);
            params.append('phone', data.phone);
            params.append('email', data.email);
            params.append('about', data.about);
            params.append('treatment', data.treatment);    
			//console.log(data.consultant);
            axios({
                method: 'post',
                url: '/customers/edit/' + data.id, 
                data: params,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                }                
            })
            .then((response) => {
                console.log(response.data);
                this.getUsersByPage(this.current_page);
            }); 
        },
        insertUserData: function(data){
            
            let params = new URLSearchParams();
            
            params.append('ichk', this.$refs.csrf.value);
            params.append('s_id', data.s_id);
            params.append('comm_id', data.comm_id);
            params.append('obj_id', data.obj_id);
            params.append('c_id', data.c_id);
            params.append('cNumber', data.cNumber);
            params.append('name', data.name);
            params.append('origin_name', data.origin_name);
            params.append('sex', data.sex);
            params.append('birth', data.birth);
            params.append('phone', data.phone);
            params.append('email', data.email);
            params.append('about', data.about);
            params.append('treatment', data.treatment);            
            
            axios({
                method: 'post',
                url: '/chinaseminar/api/activity/insert', 
                data: params,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                }                
            })
            .then((response) => {
                //console.log(response.data);
                this.getUsersByPage(this.current_page);
            }); 
        },
        deleteUserData: function(data){

            if(confirm("確定刪除?")){
                let params = new URLSearchParams();
                        
                params.append('ichk', this.$refs.csrf.value);

                axios({
                    method: 'post',
                    url: '/chinaseminar/api/activity/delete/' + data.id,          
                    data: params,
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }                
                })
                .then((response) => {
                    //console.log(response.data);
                    this.getUsersByPage(this.current_page);
                    this.getReplyedList();
                }); 
            }            
        },
    }
});