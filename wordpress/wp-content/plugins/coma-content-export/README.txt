COMA Content Export 1.0.0

Activate this plugin on the PUBLIC editorial WordPress site.
GET /wp-json/coma-sync/v1/articles?page=1
The CMS pull command imports this into a local mirror; no credentials are exported.
Only published, public, non-password-protected posts are eligible.

IMPORTANT: Do not activate on a membership/paywall site without adapting the
export to that plugin's permissions. Standard WordPress status/password checks
do not capture third-party entitlement rules.

Setup, scheduling and limits: misc/cms/WORDPRESS-PUBLISHING.md in the repository.
This does not copy image binaries or migrate users. Keep WordPress media online.
