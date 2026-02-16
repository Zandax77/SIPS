# TODO - Fix Wali Kelas Access Control Bug

## Problem Analysis
The bug is in how `$siswaIdsQuery` is being used with `whereIn()`. The variable is a query builder instance but is being passed as if it were an array of IDs.

## Bug Location
In `KendaliUtama.php`, the code did:
```php
$siswaIdsQuery = DB::table('siswas')->select('id')->when($kelasWali, ...);
// Later:
->when($kelasWali, function ($query) use ($siswaIdsQuery) {
    return $query->whereIn('id_siswa', $siswaIdsQuery); // BUG!
})
```

The `whereIn()` expects an array, but receives a query builder instance.

## Solution
Use subquery pattern with `whereIn('id_siswa', function($query) { ... })`

## Files Fixed
1. ✅ `app/Http/Controllers/KendaliUtama.php` - Fixed violation counts query in `getViolationCounts()`
2. ✅ `app/Http/Controllers/KendaliUtama.php` - Fixed chart data query in `index()` method

## Changes Made
Changed from:
```php
$siswaIdsQuery = DB::table('siswas')
    ->select('id')
    ->when($kelasWali, function ($query) use ($kelasWali) {
        return $query->where('kelas', $kelasWali);
    });
// Then incorrectly using:
->when($kelasWali, function ($query) use ($siswaIdsQuery) {
    return $query->whereIn('id_siswa', $siswaIdsQuery);
})
```

To:
```php
$siswaSubquery = function ($query) use ($kelasWali) {
    $query->select('id')->from('siswas');
    if ($kelasWali) {
        $query->where('kelas', $kelasWali);
    }
};
// Then correctly using:
->when($kelasWali, function ($query) use ($siswaSubquery) {
    return $query->whereIn('id_siswa', $siswaSubquery);
})
```

## Status
- [x] 1. Fix `getViolationCounts()` method in KendaliUtama.php
- [x] 2. Fix `index()` method chart data query in KendaliUtama.php
- [x] 3. Run database seed to verify
- [x] 4. Test Wali Kelas functionality (Server running at http://localhost:8000)

## Test Accounts
After seeding, you can test with these accounts:

| Jabatan | Email | Password | Kelas |
|---------|-------|----------|-------|
| Kesiswaan | kesiswaan@sips.test | password123 | - |
| Wali Kelas | wali.xipa1@sips.test | password123 | X IPA 1 |
| Wali Kelas | wali.xipa2@sips.test | password123 | X IPA 2 |
| Wali Kelas | wali.xips1@sips.test | password123 | X IPS 1 |
| Guru BK | bk@sips.test | password123 | - |

## Bug Fix Summary
The main bug was that `$siswaIdsQuery` was a query builder instance being passed to `whereIn()` which expects an array. Fixed by using a closure subquery pattern that Laravel's query builder understands as a subquery.

