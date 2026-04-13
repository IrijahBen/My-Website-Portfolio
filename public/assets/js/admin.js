// Admin Panel JavaScript
const API_BASE = '/api';
const AUTH_BASE = '/admin-api'; // Added specific base for auth routes

// Check authentication on load
document.addEventListener('DOMContentLoaded', async () => {
    const isLoggedIn = await checkAuth();
    if (isLoggedIn) {
        showDashboard();
    } else {
        showLogin();
    }
});

// Auth Functions
async function checkAuth() {
    try {
        const response = await fetch(`${AUTH_BASE}/check`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json' // Tells Laravel to always return JSON, even on errors
            },
            body: JSON.stringify({ action: 'check' })
        });
        const data = await response.json();
        return data.logged_in;
    } catch (error) {
        console.error('Auth check error:', error);
        return false;
    }
}

function showLogin() {
    document.getElementById('login-screen').style.display = 'flex';
    document.getElementById('dashboard').style.display = 'none';
}

function showDashboard() {
    document.getElementById('login-screen').style.display = 'none';
    document.getElementById('dashboard').style.display = 'flex';
    loadCategories();
    loadProjects();
}

// Login Form
document.getElementById('login-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch(`${AUTH_BASE}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: 'login', username, password })
        });
        const data = await response.json();

        if (data.success) {
            showDashboard();
            showToast('Login successful!', 'success');
        } else {
            showToast('Invalid credentials', 'error');
        }
    } catch (error) {
        showToast('Login failed', 'error');
    }
});

// Logout
document.getElementById('logout-btn')?.addEventListener('click', async () => {
    try {
        await fetch(`${AUTH_BASE}/logout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: 'logout' })
        });
        showLogin();
        showToast('Logged out', 'success');
    } catch (error) {
        showToast('Logout failed', 'error');
    }
});

// Tab Navigation
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        const tab = item.dataset.tab;

        // Update active nav
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        item.classList.add('active');

        // Show tab content
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.getElementById(`tab-${tab}`).classList.add('active');
    });
});

// Load Categories
async function loadCategories() {
    try {
        const response = await fetch(`${API_BASE}/categories`); // Removed .php
        const data = await response.json();

        if (data.success) {
            const selects = [document.getElementById('project-category'), document.getElementById('edit-category')];
            selects.forEach(select => {
                if (!select) return;
                select.innerHTML = '<option value="">Select Category</option>';
                data.categories.forEach(cat => {
                    if (cat.slug !== 'all') {
                        const option = document.createElement('option');
                        option.value = cat.slug;
                        option.textContent = cat.name;
                        select.appendChild(option);
                    }
                });
            });
        }
    } catch (error) {
        console.error('Failed to load categories:', error);
    }
}

// Load Projects
async function loadProjects(search = '') {
    const container = document.getElementById('projects-list');
    container.innerHTML = '<div class="loading">Loading projects...</div>';

    try {
        const url = new URL(`${API_BASE}/projects`, window.location.origin); // Removed .php
        if (search) url.searchParams.append('search', search);
        url.searchParams.append('limit', '100');

        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            renderProjects(data.projects);
        }
    } catch (error) {
        container.innerHTML = '<div class="empty-state"><h3>Error loading projects</h3></div>';
    }
}

function renderProjects(projects) {
    const container = document.getElementById('projects-list');

    if (projects.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <h3>No projects yet</h3>
                <p>Add your first project to get started</p>
            </div>
        `;
        return;
    }

    // UPDATED: Added data-category and data-author so the edit function never breaks!
    container.innerHTML = projects.map(project => `
        <div class="project-card" 
             data-id="${project.id}" 
             data-category="${escapeHtml(project.category)}"
             data-author="${escapeHtml(project.author)}"
             data-link="${project.link || ''}" 
             data-tags="${escapeHtml((project.tags || []).join(','))}">
             
            <img src="${project.image ? '/storage/' + project.image : '/assets/images/placeholder.jpg'}" alt="${project.title}" class="project-image" onerror="this.src='/assets/images/placeholder.jpg'">
            
            <div class="project-info">
                <h3 style="margin: 0 0 10px 0;">${escapeHtml(project.title)}</h3>
                
                <div class="project-bottom" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 10px;">
                    <div class="project-author" style="display: flex; align-items: center; gap: 8px; overflow: hidden;">
                        <span class="project-category" style="background: #f0f0f0; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; flex-shrink: 0;">${escapeHtml(project.category)}</span>
                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem; color: #555;">by ${escapeHtml(project.author)}</span>
                    </div>

                    <div class="project-stats" style="display: flex; align-items: center; gap: 10px; flex-shrink: 0; font-size: 0.85rem; color: #666;">
                        <span><i class="fas fa-heart"></i> ${project.likes}</span>
                        <span><i class="fas fa-eye"></i> ${project.views}</span>
                    </div>
                </div>
            </div>

            <div class="project-actions">
                <button class="btn btn-primary btn-small" onclick="editProject(${project.id})">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteProject(${project.id})">Delete</button>
            </div>
        </div>
    `).join('');
}

// Search Projects
document.getElementById('project-search')?.addEventListener('input', debounce((e) => {
    loadProjects(e.target.value);
}, 300));

// Add Project Form
document.getElementById('add-project-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch(`${API_BASE}/projects`, { // Removed .php
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData
        });
        const data = await response.json();

        if (data.success || data.id) { // Sometimes Laravel returns just id depending on the controller
            showToast('Project added successfully!', 'success');
            form.reset();
            document.getElementById('image-preview').innerHTML = '';
            document.getElementById('avatar-preview').innerHTML = '';
            loadProjects();
            // Switch to projects tab
            document.querySelector('[data-tab="projects"]').click();
        } else {
            showToast(data.error || 'Failed to add project', 'error');
        }
    } catch (error) {
        showToast('Failed to add project', 'error');
    }
});

// Image Previews
document.getElementById('project-image')?.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('image-preview').innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('author-avatar')?.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('avatar-preview').innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    }
});

// Edit Project
async function editProject(id) {
    const card = document.querySelector(`.project-card[data-id="${id}"]`);

    // UPDATED: Now we extract everything cleanly and directly from the data attributes
    const title = card.querySelector('h3').textContent.trim();
    const category = card.dataset.category || '';
    const author = card.dataset.author || '';
    const link = card.dataset.link || '';
    const tags = card.dataset.tags || '';

    // Populate the form
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-category').value = category;
    document.getElementById('edit-author').value = author;
    document.getElementById('edit-link').value = link;
    document.getElementById('edit-tags').value = tags;

    document.getElementById('edit-modal').style.display = 'flex';
}

// Edit Form Submit
document.getElementById('edit-project-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);

    try {
        // Updated to use the ID in the URL path for Laravel routing
        const response = await fetch(`${API_BASE}/projects/${data.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: new URLSearchParams(data)
        });
        const result = await response.json();

        if (result.success) {
            showToast('Project updated!', 'success');
            document.getElementById('edit-modal').style.display = 'none';
            loadProjects();
        } else {
            showToast('Failed to update', 'error');
        }
    } catch (error) {
        showToast('Update failed', 'error');
    }
});

// Close Modal
document.querySelector('.close-btn')?.addEventListener('click', () => {
    document.getElementById('edit-modal').style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
    }
});

// Delete Project
async function deleteProject(id) {
    if (!confirm('Are you sure you want to delete this project?')) return;

    try {
        // Updated to use the ID in the URL path for Laravel routing
        const response = await fetch(`${API_BASE}/projects/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();

        if (data.success) {
            showToast('Project deleted!', 'success');
            loadProjects();
        } else {
            showToast('Failed to delete', 'error');
        }
    } catch (error) {
        showToast('Delete failed', 'error');
    }
}

// Utility Functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}
