<template>
  <div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Blog</h1>
    </div>
    <div class="row">
      <!-- Area Chart -->
      <div class="col-md-12">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Blog</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Main Image</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Content</th>
                    <th colspan="2">Action</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>No.</th>
                    <th>Main Image</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Content</th>
                    <th colspan="2">Action</th>
                  </tr>
                </tfoot>
                <tbody>
                  <tr v-for="(blog, index) in blogs" :key="blog.id">
                      <td>{{index+1}}</td>
                      <td><img :src="'../storage/'+blog.main_image" :alt="blog.title" width="100px"></td>
                      <td>{{blog.title}}</td>
                      <td v-html="blog.slug_title"></td>
                      <td v-html="blog.description"></td>
                      <td><button class="btn btn-info" @click="editBlog(blog.id)">Edit </button></td>
                      <td><button class="btn btn-danger" @click="deleteBlog(index)">Remove </button></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Pie Chart -->
      <div class="col-md-12">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-description-between">
            <h6 class="m-0 font-weight-bold text-primary">Add New Blog</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="row ">
                <div class="col-md-12 text-center">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors" :key="error">{{error}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12 mb-3 mb-sm-0">
                <label>Main Image</label><br>
                 <img :src="blog.main_image" class="img-responsive" style="max-height:350px" v-if="blog.main_image!=''">
                <input type="file" class="form-control form-control-user" placeholder="Main Image" @change="onMainImageChange" id="main_image_upload" accept="image/*">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12 mb-3 mb-sm-0">
                <label>Title</label>
                <input type="text" class="form-control form-control-user"  placeholder="Title" v-model="blog.title">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12">
                <label>Content</label>
                <editor v-model="blog.description"

                api-key="gssljk5uv5x6t2kif8srfvvuzj9g22oqase8m28dqkqrr5e4"
                :init="{
                  height: 300,
                  menubar: false,
                  mode : 'textareas',
                  force_br_newlines : false,
                  force_p_newlines : false,
                  forced_root_block : '',
                  entity_encoding: 'raw',
                  relative_urls : false,
                  remove_script_host : false,
                  convert_urls : false,
                  plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                  ],
                  toolbar:
                    'formatselect | bold italic backcolor | \
                    alignleft aligncenter alignright alignjustify | \
                    bullist numlist outdent indent | removeformat | image'
                }"
              />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12">
                <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" @click="createBlog()"><i class="fas fa-plus fa-sm text-white-50"></i> Add New Blog</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    import Editor from '@tinymce/tinymce-vue'
    export default {
        data(){
            return{
                blog:{
                    id:"",
                    main_image:"",
                    title:"",
                    slug_title:"",
                    description:"",
                },
                errors: [],
                blogs: [],
                uri:'/api/resource/blog',
            }
        },
        components:{
          'editor':Editor
        },
        methods:{
            loadBlogs(){
                axios.get(this.uri).then(response=>{
                  this.blogs = response.data.blogs
                });
            },
            onMainImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createMainImage(files[0]);
            },
            createMainImage(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.blog.main_image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            createBlog(){
              let formData = new FormData();
              var main_image_file = document.querySelector('#main_image_upload');
              formData.append('title',this.blog.title);
              formData.append('description',this.blog.description);
              formData.append('main_image',main_image_file.files[0]);
                axios.post(this.uri,formData,{
                  headers: {
                    'Content-Type': 'multipart/form-data'
                  }
                }).then(response=>{
                        this.blogs.push(response.data.blogs);
                        toastr.success(response.data.status);
                        this.resetData();
                }).catch(error=>{
                    if (error.response.data.name){
                        this.errors.push(error.response.data.name[0]);
                    }
                });
            },
            editBlog(id) {
              window.location.href = "/admin/blog/" + id;
            },
            deleteBlog(index){
                let confirmBox = confirm("Do you really want to delete this?");
                if(confirmBox==true){
                    axios.delete(this.uri+"/"+this.blogs[index].id).then(
                        response=>{
                            this.$delete(this.blogs,index);
                            toastr.success(response.data.status);
                        }).catch(error=>{
                            console.log("Could not delete for some reason");
                        });
                }
            },
            resetData(){
                this.errors=[];
                var main_image_file = document.querySelector('#main_image_upload');
                main_image_file.value='';
                this.blog.title = '';
                this.blog.description = '';
                this.blog.main_image = '';
            },
        },
        mounted() {
            this.loadBlogs();
            console.log('Blogs mounted.')
        }
    }
</script>