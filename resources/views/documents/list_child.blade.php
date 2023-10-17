<div class="card animate fadeUp">
   <div class="card-content">
      <div class="row" id="product-four">
         <div class="col s12 m6">
            <h5><span class="chip pink lighten-5"><span class="pink-text">Scoping - Pending Action</span></span></h5>
            <img src="http://127.0.0.1:8000/admin/images/demo_pdf.png" class="responsive-img" style="max-width: 75% !important" alt="">
         </div>
         <div class="col s12 m6">
            <p style="text-align: right;"></p>
            <table class="striped">
               <tbody>
                  <tr>
                     <td>Date of Entry:</td>
                     <td>2022-04-07 14:40:00</td>
                  </tr>
                  <tr>
                     <td>Uploaded By:</td>
                     <td>Adithya</td>
                  </tr>
                  <tr>
                     <td>Description:</td>
                     <td>sdfsfsdf20</td>
                  </tr>
               </tbody>
            </table>
         </div>
         <div class="col s12 m12">
            @can('documents-comment-create')
               <div class="row commentContainer" id="commentContainer20">
                  <div class="input-field col m10 s12 commentArea">
                     <textarea id="comment" class="materialize-textarea commentField" name="comment" cols="50" rows="10"></textarea>
                     <label for="comment" class="label-placeholder active"> Comments </label>
                     <div id="documentComment-error-20" class="error documentComment-error" style="display:none;"></div>
                  </div>
                  <div class="input-field col m2 s12" style="margin-top: 37px; ! important"><a href="javascript:" class="text-sub save-comment-btn" data-id="11"><i class="material-icons mr-2"> send </i></a></div>
               </div>
            @endcan
            <div class="app-email" id="">
               <div class="content-area">
                  <div class="app-wrapper">
                     <div class="card card card-default scrollspy border-radius-6 fixed-width">
                        <div class="card-content p-0 pb-2">
                           <div class="collection email-collection">
                              <div class="email-brief-info collection-item animate fadeUp ">
                                 <a class="list-content" href="javascript:">
                                    <div class="list-title-area">
                                       <div class="user-media">
                                          <img src="http://127.0.0.1:8000/admin/images/user-icon.png" alt="" class="circle z-depth-2 responsive-img avtar">
                                          <div class="list-title">Adithya</div>
                                       </div>
                                    </div>
                                    <div class="list-desc">This is my second comment</div>
                                 </a>
                                 <div class="list-right">
                                    <div class="list-date">Apr 11, 07:18 AM </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>