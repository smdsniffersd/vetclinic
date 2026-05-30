
document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.account-nav li');
    const tabs = document.querySelectorAll('.tab-content');
    
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            

            navItems.forEach(nav => nav.classList.remove('active'));
            tabs.forEach(tab => tab.classList.remove('active'));
            

            this.classList.add('active');
            document.getElementById(`tab-${tabId}`).classList.add('active');
        });
    });

    const editBtn = document.getElementById('editProfileBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const profileView = document.getElementById('profileView');
    const profileEdit = document.getElementById('profileEdit');
    
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            profileView.style.display = 'none';
            profileEdit.style.display = 'block';
        });
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            profileView.style.display = 'block';
            profileEdit.style.display = 'none';
        });
    }
});