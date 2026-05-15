## CI Fixes
- Updated ESLint config to ignore .agents/, .claude/, and .cursor/.
- Fixed unused variables in show.tsx.
- Fixed lint errors (curly braces and padding) in welcome.tsx.
- Dropped PHP 8.3 from CI matrix as composer.lock requires 8.4+.
- Fixed Python SDK package name to match tests and README.
