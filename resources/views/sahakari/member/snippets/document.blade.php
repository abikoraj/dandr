<div id="farmer-data" class="card shadow p-2">
    <h4 class="title d-flex justify-content-between">
        <span>Documents</span>
        <span>
            <span class="btn btn-primary btn-sm" onclick="addDoc()">Add Document</span>
            <span class="btn btn-primary btn-sm toogle ml-2" data-on="true" data-collapse="#documents">
                <span class="on">Hide</span>
                <span class="off">show</span>
            </span>
        </span>
    </h4>
    <hr class="mt-0 mb-1">
    <div id="documents" style="min-height: 100px">
        <h4 class="h-100 text-center my-5" id="d-none">
            No Documents. Press "Add Document" To Add New Document
        </h4>
    </div>
</div>

@section('js2')
    <script>
        doc_id = 0;
        img_id = 0;
        // $('#doc-image-' + doc_id+"-0").dropify();
        // $('#doc-image-' + doc_id+"-1").dropify();

        function addDoc() {
            html = '<div class="p-3 doc" id="doc-' + doc_id + '">' +
                '<div class="shadow p-2">' +
                ' <div class="row">' +
                ' <div class="col-md-12">' +
                '<label class="w-100 d-flex justify-content-between">' +
                '<span>Images/Scans</span>' +
                '<span>' +
                ' <span class="btn btn-primary btn-sm" onclick="addImage(' + doc_id + ')">Add Image</span>' +
                '<span class="btn btn-danger btn-sm" onclick="removeDoc(' + doc_id + ')">Remove Document</span>' +
                '</span>' +
                '</label>' +
                '<div class="row" id="images-' + doc_id + '">' +
                ' <div class="col-md-3 p-r image-' + doc_id + '" id="doc-image-wrapper-' + img_id + '">' +
                '<button class="btn-close" onclick="removeImage(' + img_id + ',' + doc_id + ')">&times;</button>' +
                '<input type="file" name="doc-image-' + doc_id + '[]" id="doc-image-' + img_id +
                '" accept="image/*,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>' +
                '</div>' +

                ' </div>' +
                '<hr>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="Document">Document Name</label>' +
                '<input type="text" id="dt-' + doc_id + '" name="dt-' + doc_id + '" required class="form-control">' +
                '<input type="hidden"  name="d[]" value="' + doc_id + '">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="Document">Document No</label>' +
                '<input type="text" id="dn-' + doc_id + '" name="dn-' + doc_id + '" required class="form-control">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="Document">Issue Date</label>' +
                '<input type="text" id="di-' + doc_id + '" name="di-' + doc_id + '" class="form-control">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="Document">Issued From</label>' +
                '<input type="text" id="dif-' + doc_id + '" name="dif-' + doc_id + '" class="form-control">' +
                '</div>' +
                '</div>' +
                ' </div>' +
                '</div>';
            $('#documents').append(html);
            $('#doc-image-' + img_id).dropify();
            setDate('di-' + doc_id);
            doc_id = parseInt(doc_id) + 1;
            img_id = parseInt(img_id) + 1;
            $('#d-none').addClass('d-none');
        }

        function addImage(docid) {
            html = ' <div class="col-md-3 p-r image-' + docid + '" id="doc-image-wrapper-' + img_id + '">' +
                '<button class="btn-close" onclick="removeImage(' + img_id + ',' + docid + ')">&times;</button>' +
                '<input type="file" name="doc-image-' + doc_id + '[]" id="doc-image-' + img_id +
                '" accept="image/*,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>' +
                '</div>';
            $('#images-' + docid).append(html);
            $('#doc-image-' + img_id).dropify();
            doc_id = parseInt(doc_id) + 1;
            img_id = parseInt(img_id) + 1;
        }

        function removeDoc(id) {
            if (confirm('Do You Want To delete Document')) {
                $('#doc-' + id).remove();
                if ($('.doc').length == 0) {
                    $('#d-none').removeClass('d-none');

                }
            }
        }

        function removeImage(id, docid) {
            console.log(id, docid, 'remove-image', $('.image-' + docid).length);
            if ($('.image-' + docid).length == 1) {
                removeDoc(docid);
            } else {
                $('#doc-image-wrapper-' + id).remove();
            }
        }
    </script>
@endsection
