import moodle_docs_theme

project = "moodle-repository_eduplay"
copyright = "2026, EduPlay Moodle Suite contributors"
author = "Kelson da Costa Medeiros"
release = "0.1.0"

extensions = [
    "sphinx.ext.githubpages",
    "moodle_docs_theme",
]

templates_path = []
exclude_patterns = ["_build", "Thumbs.db", ".DS_Store"]

language = "en"

html_theme = "moodle_docs_theme"
html_theme_path = [moodle_docs_theme.get_html_theme_path()]

html_theme_options = {
    "primary_color": "#6c336d",
    "secondary_color": "#f98012",
    "project_name": "moodle-repository_eduplay",
    "tagline": "File picker repository to add EduPlay video links in Moodle (PoC, unofficial)",
    "github_url": "https://github.com/eduplay-moodle-suite/moodle-repository_eduplay",
    "github_repo": "eduplay-moodle-suite/moodle-repository_eduplay",
    "github_version": "main",
    "doc_path": "docs/en/",
    "show_edit_on_github": True,
    "enable_dark_mode": True,
    "navigation_links": "Home|index, Installation|installation, Configuration|configuration, Usage|usage",
}

html_static_path = []
