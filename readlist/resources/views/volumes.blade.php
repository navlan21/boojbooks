<html>
    <head>
      <title>Read List Generator</title>
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
      <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
   </head>
   <style>


   </style>
   <body>
   <H1>Books Read List Builder</H1>
   <div class="container">
      <div class="row">
         <div class="col"></div>
         <div class="col"></div>
         <div class="col"></div>
         <div class="col"></div>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-6 mb-3 mb-md-0">
         <div class="card">
            <div class="card-body">
            <h5 class="card-title">Select a book from Google Books List:</h5>
            <button id="displayBook" type="button" class="btn btn-success" data-toggle="modal" data-target="#displayModal">Display detail</button>
            <button id="postButton" type="submit" class="btn btn-success">Add selected book to Read List</button>
            <table  class="table" >
               <thead>
                  <TR>
                     <TD>ID</TD>
                     <TD>Title</TD>
                     <TD>Author</TD>
                     <TD>Description</TD>
                     <TD>Cover</TD>
                     <TD></TD>
                  </TR>
               </thead>
               <tbody>
                  @foreach($data as $item) 
                     <TR>
                        <TD>{{$item['id']}}</TD>
                        <TD>{{$item['volumeInfo']['title']}}</TD>
                        <TD>{{$item['volumeInfo']['authors'][0]}}</TD>
                        <TD>{{$item['volumeInfo']['industryIdentifiers'][0]['identifier']}}</TD>
                        <TD><img src="{{$item['volumeInfo']['imageLinks']['smallThumbnail']}}"></TD>
                        <TD>
                           <form id="{{$item['id']}}">
                              <input type="hidden" name="id" value="{{$item['id']}}">
                              <input type="hidden" name="title" value="{{$item['volumeInfo']['title']}}">
                              <input type="hidden" name="author" value="{{$item['volumeInfo']['authors'][0]}}">
                              <input type="hidden" name="description" value="{{$item['volumeInfo']['description']}}">
                              <input type="hidden" name="ISBN" value="{{$item['volumeInfo']['industryIdentifiers'][0]['identifier']}}">
                              <input type="hidden" name="thumbNail" value="{{$item['volumeInfo']['imageLinks']['smallThumbnail']}}">
                           <input type="checkbox" class="checkboxGrp" >
                           </form>
                        </TD>
                     </TR>
                  @endforeach
               </tbody>
            </table>
            </div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="card">
            <div class="card-body">
            <H1>My Read List</H1><H6>(Click header to sort)</H6>
               <table id="readList"  class="table">
                  <thead>
                     <tr>
                        <th><a href="#!" onclick="sortBy('title');" > Title</a></th>
                        <th><a href="#!" onclick="sortBy('Author');" > Author</a></th>
                        <th><a href="#!" onclick="sortBy('ISBN');" > ISBN</a></th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody id="tableBody"></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- Modal -->
   <div class="modal fade" id="displayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLabel">Quick View</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </button>
         </div>
         <div id="volumeDetail" class="modal-body">
            <div class="card border-secondary mb-3" style="max-width: 18rem;">
            <div  class="card-header">By: <span id="bookAuthor">-</span></div>
            <div class="card-body text-secondary">
               <h5 id="bookTitle" class="card-title">Book is not Selected</h5>
               <p id="bookDes" class="card-text">Select the book and then click display</p>
            </div>
            </div>
            <img id="bookImage" src="">
         </div>
         <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
   </div>

   <script>
      var sortString = '';
      //populate the Read list table
      function readList() {
         trHTML = '';
         $('#tableBody').empty();
         $.ajax({
            type:'GET',
            url:"{{ url('/api/posts') }}" + sortString,
            dataType: 'json',
            success:function(data) {
               $.each(data, function (i, item) {
               trHTML += '<tr><td>' + item['title'] + '</td><td>' + item['Author'] + '</td><td>' + item['ISBN'];
               trHTML += '</td><td><button onclick="deleteItem(' + item['id'] + ');" data-del-id="' + item['id'];
               trHTML += '" type="button" class="btn btn-outline-secondary btn-sm">Delete</button></td></tr>';
               });
               $('#tableBody').append(trHTML);
            }
         });
      }
      readList();

      function sortBy(sortBy) {
         sortString = "/sorted/" + sortBy;
         readList();
      }

      var selectedForm = "";
      $('.checkboxGrp').click(function uncheckAll(){
         $('input[type="checkbox"]:checked').prop('checked',false);
         $(this).prop('checked',true);
         selectedForm = $(this).parents('form');
      });
         
      $('#displayBook').click(function(e){   
         $('#bookAuthor').text($(selectedForm).serializeArray()[2].value);
         $('#bookTitle').text($(selectedForm).serializeArray()[1].value);
         $('#bookDes').text($(selectedForm).serializeArray()[3].value);
         $('#bookImage').attr("src", $(selectedForm).serializeArray()[5].value);     
      });

      $('#postButton').click(function(e){
         $.ajax({
            type:'POST',
            url:"{{ url('/api/posts') }}",
            data:{
               'title':$(selectedForm).serializeArray()[1].value,
               'Author': $(selectedForm).serializeArray()[2].value,
               'Description':$(selectedForm).serializeArray()[3].value,
               'ISBN':$(selectedForm).serializeArray()[4].value
            },
            dataType: 'json',
            success:function(data) {
               console.log(data);
               readList();
               console.log('show read list');
            }
         });
      });

      function deleteItem(id) {
         $.ajax({
            type:'DELETE',
            url:"{{ url('/api/posts/') }}/" + id ,
            dataType: 'json',
            success:function(data) {
               readList();
            }
         });
      }
   </script>
   </body>
</html>
