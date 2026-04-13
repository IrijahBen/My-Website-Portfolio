// Portfolio Showcase Frontend JavaScript
var API_BASE = '/api'; // 'var' prevents the redeclaration error, '/api' points to Laravel's api routes

// State
let currentCategory = 'all';
let currentSearch = '';
let currentOffset = 0;
const limit = 12;
let hasMore = true;
let isLoading = false;

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadProjects();
    setupEventListeners();
});

// Setup Event Listeners
function setupEventListeners() {
    // Category tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentCategory = btn.dataset.category;
            currentOffset = 0;
            hasMore = true;
            loadProjects(true);
        });
    });

    // Search input
    const searchInput = document.getElementById('search-input');
    searchInput.addEventListener('input', debounce((e) => {
        currentSearch = e.target.value;
        currentOffset = 0;
        hasMore = true;
        loadProjects(true);
    }, 300));

    // Load more button
    document.getElementById('load-more-btn').addEventListener('click', () => {
        if (!isLoading && hasMore) {
            loadProjects(false);
        }
    });

    // Footer category links
    document.querySelectorAll('.footer-column a[data-category]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const category = link.dataset.category;
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.category === category) {
                    btn.classList.add('active');
                }
            });
            currentCategory = category;
            currentOffset = 0;
            hasMore = true;
            loadProjects(true);
            document.getElementById('showcase').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Showcase button - Updated to point to Laravel's absolute admin route
    document.getElementById('showcase-btn').addEventListener('click', () => {
        window.location.href = '/admin';
    });
}

// Load Projects
async function loadProjects(reset = false) {
    if (isLoading) return;
    isLoading = true;

    const grid = document.getElementById('projects-grid');
    const loadMoreBtn = document.getElementById('load-more-btn');

    if (reset) {
        grid.innerHTML = createSkeletonLoader(6);
        loadMoreBtn.style.display = 'none';
    }

    try {
        // Updated to remove .php and use window.location.origin for absolute routing
        const url = new URL(`${API_BASE}/projects`, window.location.origin);

        url.searchParams.append('category', currentCategory);
        url.searchParams.append('limit', limit.toString());
        url.searchParams.append('offset', currentOffset.toString());
        if (currentSearch) {
            url.searchParams.append('search', currentSearch);
        }

        const response = await fetch(url);

        // Added a safety check to catch non-JSON errors gracefully
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            if (reset) {
                grid.innerHTML = '';
            }

            if (data.projects.length === 0 && reset) {
                grid.innerHTML = createEmptyState();
            } else {
                data.projects.forEach(project => {
                    grid.appendChild(createProjectCard(project));
                });
            }

            // Update results count
            const resultsCount = document.querySelector('.results-count');
            const totalShown = Math.min(currentOffset + data.projects.length, data.total);
            resultsCount.textContent = `Showing ${totalShown} of ${data.total} projects`;

            // Update load more button
            hasMore = data.hasMore;
            loadMoreBtn.style.display = hasMore ? 'inline-flex' : 'none';

            currentOffset += data.projects.length;
        }
    } catch (error) {
        console.error('Failed to load projects:', error);
        if (reset) {
            grid.innerHTML = createErrorState();
        }
    } finally {
        isLoading = false;
    }
}

// Create Project Card
function createProjectCard(project) {
    const card = document.createElement('div');
    card.className = 'project-card';

    // CORRECTED: Added '/storage/' to correctly map to Laravel's storage link
    const imagePath = project.image ? '/storage/' + project.image : '/assets/images/placeholder.jpg';
    const avatarPath = project.author_avatar ? '/storage/' + project.author_avatar : '/assets/images/default-avatar.jpg';

    card.innerHTML = `
        <div class="project-image">
            <img src="${imagePath}" 
                 alt="${escapeHtml(project.title)}" 
                 loading="lazy"
                 onerror="this.src='/assets/images/placeholder.jpg'">
            <div class="project-overlay">
                <div class="project-tags">
                    ${(project.tags || []).slice(0, 3).map(tag =>
        tag ? `<span class="project-tag">${escapeHtml(tag)}</span>` : ''
    ).join('')}
                </div>
            </div>
        </div>
        <div class="project-info">
            <h3 class="project-title">${escapeHtml(project.title)}</h3>
            <div class="project-author">
                <img src="${avatarPath}" 
                     alt="${escapeHtml(project.author)}" 
                     class="author-avatar"
                     onerror="this.src='/assets/images/default-avatar.jpg'">
                <span class="author-name">${escapeHtml(project.author)}</span>
                
                <div class="project-stats" style="display: flex; align-items: center; gap: 12px; flex-shrink: 0; font-size: 0.85rem; color: #666;">
                    ${project.link ? `<a href="${project.link}" target="_blank" class="btn btn-primary btn-small demo-link" style="text-decoration: none; font-size: 0.8rem; padding: 4px 8px; margin-right: auto;" onclick="event.stopPropagation();">Live Demo</a>` : ''}
                    
                    <span><i class="fas fa-heart"></i> ${formatNumber(project.likes)}</span>
                    <span><i class="fas fa-eye"></i> ${formatNumber(project.views)}</span>
                </div>
            </div>
        </div>
    `;

    // Add click handler (Retained from your original code)
    card.addEventListener('click', () => {
        if (project.link) {
            window.open(project.link, '_blank');
        }
    });

    return card;
}

// Create Skeleton Loader
function createSkeletonLoader(count) {
    let html = '';
    for (let i = 0; i < count; i++) {
        html += `
            <div class="skeleton-card">
                <div class="skeleton-image"></div>
                <div class="skeleton-content">
                    <div class="skeleton-title"></div>
                    <div class="skeleton-meta"></div>
                </div>
            </div>
        `;
    }
    return html;
}

// Create Empty State
function createEmptyState() {
    return `
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>No projects found</h3>
            <p>Try adjusting your search or category filter</p>
        </div>
    `;
}

// Create Error State
function createErrorState() {
    return `
        <div class="empty-state">
            <i class="fas fa-exclamation-circle"></i>
            <h3>Failed to load projects</h3>
            <p>Please try again later</p>
        </div>
    `;
}

// Utility Functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'k';
    }
    return num.toString();
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

// Intersection Observer for lazy loading animations
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

// Observe elements after they are added to DOM
function observeElements() {
    document.querySelectorAll('.project-card').forEach(card => {
        observer.observe(card);
    });
}
