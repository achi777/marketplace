<form class="upload-form" data-doc-type="{{ $docType }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <p class="text-muted small">{{ $description }}</p>
    </div>
    
    <div class="row">
        <div class="col-md-8 mb-3">
            <label for="{{ $docType }}_file" class="form-label">Choose File</label>
            <input type="file" 
                   class="form-control" 
                   id="{{ $docType }}_file" 
                   name="document_file" 
                   accept=".jpg,.jpeg,.png,.pdf"
                   required>
            <div class="form-text">Supported formats: JPG, PNG, PDF (max 5MB)</div>
        </div>
        
        <div class="col-md-4 mb-3">
            <label for="{{ $docType }}_number" class="form-label">Document Number <small>(optional)</small></label>
            <input type="text" 
                   class="form-control" 
                   id="{{ $docType }}_number" 
                   name="document_number" 
                   placeholder="ID/License number">
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center">
        <div class="upload-progress" style="display: none;">
            <div class="progress" style="width: 200px;">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted">Uploading...</small>
        </div>
        
        <button type="submit" class="btn btn-primary upload-btn">
            <i class="bi bi-upload"></i> Upload Document
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.upload-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const docType = this.dataset.docType;
            const formData = new FormData(this);
            formData.append('document_type', docType);
            
            const uploadBtn = this.querySelector('.upload-btn');
            const uploadProgress = this.querySelector('.upload-progress');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            
            // Show loading state
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
            uploadProgress.style.display = 'block';
            
            // Simulate progress (since we can't get real upload progress easily)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 30;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
            }, 200);
            
            fetch('{{ route('kyc.upload') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                    // Reset form
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Document';
                    uploadProgress.style.display = 'none';
                    progressBar.style.width = '0%';
                }
            })
            .catch(error => {
                clearInterval(progressInterval);
                console.error('Error:', error);
                showNotification('Upload failed. Please try again.', 'error');
                
                // Reset form
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Document';
                uploadProgress.style.display = 'none';
                progressBar.style.width = '0%';
            });
        });
    });
});
</script>