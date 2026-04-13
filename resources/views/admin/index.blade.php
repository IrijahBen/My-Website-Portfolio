<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Portfolio Showcase</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="app">
        <!-- Login Screen -->
        <div id="login-screen" class="login-screen" style="display: none;">
            <div class="login-box">
                <h1>Admin Login</h1>
                <form id="login-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="hint">Default: admin / admin123</p>
            </div>
        </div>

        <!-- Admin Dashboard -->
        <div id="dashboard" class="dashboard" style="display: none;">
            <aside class="sidebar">
                <div class="sidebar-header">
                    <h2>Portfolio Admin</h2>
                </div>
                <nav class="sidebar-nav">
                    <a href="#" class="nav-item active" data-tab="projects">Projects</a>
                    <a href="#" class="nav-item" data-tab="add">Add New</a>
                    <a href="#" class="nav-item" data-tab="settings">Settings</a>
                </nav>
                <div class="sidebar-footer">
                    <button id="logout-btn" class="btn btn-secondary">Logout</button>
                </div>
            </aside>

            <main class="main-content">
                <!-- Projects Tab -->
                <div id="tab-projects" class="tab-content active">
                    <header class="content-header">
                        <h1>All Projects</h1>
                        <div class="search-box">
                            <input type="text" id="project-search" placeholder="Search projects...">
                        </div>
                    </header>
                    <div id="projects-list" class="projects-list">
                        <!-- Projects will be loaded here -->
                    </div>
                </div>

                <!-- Add New Tab -->
                <div id="tab-add" class="tab-content">
                    <header class="content-header">
                        <h1>Add New Project</h1>
                    </header>
                    <form id="add-project-form" class="project-form" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="project-title">Project Title *</label>
                                <input type="text" id="project-title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="project-category">Category *</label>
                                <select id="project-category" name="category" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="project-description">Description</label>
                            <textarea id="project-description" name="description" rows="4"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="project-author">Author Name *</label>
                                <input type="text" id="project-author" name="author" required>
                            </div>
                            <div class="form-group">
                                <label for="project-link">Project Link</label>
                                <input type="url" id="project-link" name="link" placeholder="https://...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="project-tags">Tags (comma separated)</label>
                            <input type="text" id="project-tags" name="tags" placeholder="web design, animation, portfolio">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="project-image">Project Image *</label>
                                <input type="file" id="project-image" name="image" accept="image/*" required>
                                <div id="image-preview" class="image-preview"></div>
                            </div>
                            <div class="form-group">
                                <label for="author-avatar">Author Avatar</label>
                                <input type="file" id="author-avatar" name="author_avatar" accept="image/*">
                                <div id="avatar-preview" class="image-preview small"></div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Add Project</button>
                            <button type="reset" class="btn btn-secondary">Clear</button>
                        </div>
                    </form>
                </div>

                <!-- Settings Tab -->
                <div id="tab-settings" class="tab-content">
                    <header class="content-header">
                        <h1>Settings</h1>
                    </header>
                    <div class="settings-info">
                        <p>Default admin credentials:</p>
                        <code>Username: admin</code><br>
                        <code>Password: admin123</code>
                        <p class="note">To change credentials, modify the database directly or update the auth system.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Edit Project</h2>
            <form id="edit-project-form">
                <input type="hidden" id="edit-id" name="id">
                <div class="form-group">
                    <label for="edit-title">Title</label>
                    <input type="text" id="edit-title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="edit-description">Description</label>
                    <textarea id="edit-description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit-category">Category</label>
                    <select id="edit-category" name="category" required></select>
                </div>
                <div class="form-group">
                    <label for="edit-author">Author</label>
                    <input type="text" id="edit-author" name="author" required>
                </div>
                <div class="form-group">
                    <label for="edit-link">Link</label>
                    <input type="url" id="edit-link" name="link">
                </div>
                <div class="form-group">
                    <label for="edit-tags">Tags (comma separated)</label>
                    <input type="text" id="edit-tags" name="tags">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
