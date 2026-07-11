# Changelog

## Unreleased

**Highlight**

- **Database cleanup:** Clean revisions, drafts, trash, spam, expired transients, orphaned meta, unused terms, and optimize tables. (#26)
- **Limit login attempts:** Block an IP after 3 failed logins for 1 hour. (#25)
- Allow `.avif` uploads when restricting file types. (#24)
- Customize maintenance mode via `maintenance.php` in the active theme.

**Other changes**

- Reorganize settings into grouped tabs with sub-tabs.
- Polish settings copy and option descriptions.
- Add a documentation link in the settings tabs bar.
- Fix cache clearing when an approved comment is inserted.
- Harden cache cleanup and request URI handling on PHP 8.
