<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    </main>
    <!-- /.container-fluid -->

    <!-- Footer -->
    <footer class="bg-white border-top py-3 px-4 text-center text-sm-start d-flex flex-column flex-sm-row justify-content-between align-items-center mt-auto">
        <div class="text-muted small">
            &copy; <?= date('Y'); ?> <strong>POS & Inventory System</strong>. All rights reserved.
        </div>
        <div class="text-muted small">
            Built with <span class="text-danger"><i class="bi bi-heart-fill"></i></span> using CodeIgniter 3 & Bootstrap 5
        </div>
    </footer>
</div>
<!-- /#page-content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- jQuery 3.7.1 CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 Bundle JS via CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 via CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Global Scripting & CSRF Interceptor -->
<script>
    // Global CSRF Token Variables for AJAX requests
    var csrfTokenName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfTokenHash = '<?= $this->security->get_csrf_hash(); ?>';

    $(document).ready(function() {
        // Sidebar Toggle for Mobile / Tablets
        $('#sidebarToggle').on('click', function(e) {
            e.preventDefault();
            $('#sidebar').toggleClass('toggled');
        });

        // Automatically append CSRF token to all POST AJAX requests
        $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
            if (options.type.toLowerCase() === 'post') {
                if (typeof options.data === 'string' && options.data.indexOf(csrfTokenName) === -1) {
                    options.data += (options.data ? '&' : '') + csrfTokenName + '=' + encodeURIComponent(csrfTokenHash);
                } else if (typeof options.data === 'object' && !options.data instanceof FormData) {
                    options.data[csrfTokenName] = csrfTokenHash;
                } else if (options.data instanceof FormData) {
                    if (!options.data.has(csrfTokenName)) {
                        options.data.append(csrfTokenName, csrfTokenHash);
                    }
                }
            }
        });

        // Update CSRF token hash if returned in AJAX JSON response (when csrf_regenerate = TRUE)
        $(document).ajaxComplete(function(event, xhr, settings) {
            try {
                var res = JSON.parse(xhr.responseText);
                if (res && res.csrf_token) {
                    csrfTokenHash = res.csrf_token;
                    $('input[name="' + csrfTokenName + '"]').val(csrfTokenHash);
                }
            } catch (e) {
                // Response is not JSON or no token returned
            }
        });
    });

    /**
     * SweetAlert2 Global Confirm Delete Helper
     * @param {string} deleteUrl URL to navigate to or submit
     * @param {string} itemName Name of the item being deleted
     */
    function confirmDelete(deleteUrl, itemName = 'data ini') {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Data "${itemName}" akan dihapus permanen dari sistem!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus Data!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    }
</script>
</body>
</html>
