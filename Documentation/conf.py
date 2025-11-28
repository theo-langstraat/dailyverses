# Configuration file for the Sphinx documentation builder.
#
# For the full list of built-in configuration values, see the documentation:
# https://www.sphinx-doc.org/en/master/usage/configuration.html

# -- Project information -----------------------------------------------------
# https://www.sphinx-doc.org/en/master/usage/configuration.html#project-information

from datetime import datetime
copyright = f"2025–{datetime.now().year}, Theo Langstraat"

project = 'dailyverses'
#copyright = '2025-%Y, Theo Langstraat '
author = 'Theo Langstraat'
release = '13.4.8'
theme_project_home = ''

# -- General configuration ---------------------------------------------------
# https://www.sphinx-doc.org/en/master/usage/configuration.html#general-configuration

extensions = []

templates_path = ['_templates']
exclude_patterns = ['_build', 'Thumbs.db', '.DS_Store']

# -- Options for HTML output -------------------------------------------------
# https://www.sphinx-doc.org/en/master/usage/configuration.html#options-for-html-output

html_theme = 'sphinx_typo3_theme'

# Thes options generate an error!
#html_theme_options = {
#    "github_repo": "your/repo",  # optioneel
#    "github_banner": True,
#    "show_related": False,
#    "navigation_depth": 4,
#}

# html_theme_path = ['/home/serveradmin/pruts/sphinx_typo3_theme/']

html_context = {
    'theme_project_home': 'https://www.langstraatonline.nl',
    'theme_project_contact': 'mailto:theo.langstraat@delta.nl',
    'theme_project_discussions': '',
    'theme_project_issues': '',
    'theme_project_repository': '',
    'theme_version': '4.9.0',

#    'theme_project_repository': 'https://github.com/theolangstraat/dailyverses',
#    'theme_project_issues': 'https://github.com/theolangstraat/dailyverses/issues',
#    'theme_project_discussions': 'https://github.com/theolangstraat/dailyverses/discussions',

    'style': 'custom.css',
}

html_static_path = ['_static']
html_css_files = ['custom.css']
#html_js_files = ['sphinx_highlight.js']

latex_engine = 'xelatex'

latex_elements = {
    'preamble': r'''
\usepackage{longtable}
''',
}
