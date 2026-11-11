<?php
/* Template Name: Certificates Page */
get_header();
?>

<div class="container" style="padding: 60px 0;">
    <h2 style="text-align:center;">Check Your Certificate</h2>

    <form id="certificate-form" style="max-width:500px;margin:30px auto;text-align:center;">
        <input type="text" id="student-id" name="student_id" placeholder="Enter Student ID" required
            style="padding:10px;width:70%;border:1px solid #ccc;border-radius:4px;">
        <button type="submit"
            style="padding:10px 20px;background-color:#2b6cb0;color:white;border:none;border-radius:4px;cursor:pointer;">
            Get Certificate
        </button>
    </form>

    <div id="certificate-result" style="text-align:center;margin-top:30px;"></div>
</div>

<script>
    document.getElementById("certificate-form").addEventListener("submit", function (e) {
        e.preventDefault();

        const studentId = document.getElementById("student-id").value.trim();
        if (!studentId) {
            alert("Please enter a student ID");
            return;
        }

        // Directly build the URL without date folder
        const pdfUrl = `https://www.iahsaward.org.uk/certificates/${studentId}.pdf`;

        fetch(pdfUrl, { method: 'HEAD' })
            .then(res => {
                if (res.ok) {
                    document.getElementById("certificate-result").innerHTML = `
          <iframe 
            src="${pdfUrl}#toolbar=0&navpanes=0&scrollbar=0"
            width="100%" 
            height="600px"
            style="border:none;">
          </iframe>
        `;
                } else {
                    document.getElementById("certificate-result").innerHTML = `
          <p style="color:red;">❌ Certificate not found for Student ID: ${studentId}</p>
        `;
                }
            })
            .catch(() => {
                document.getElementById("certificate-result").innerHTML = `
        <p style="color:red;">⚠️ Error checking certificate. Please try again later.</p>
      `;
            });
    });
</script>

<?php get_footer(); ?>