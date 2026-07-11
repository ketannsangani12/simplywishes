<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Reports used to store every comment as reportable_type 'comment',
     * which is ambiguous across the four comment tables. Resolve each row
     * to its specific type by matching the comment id and reported author,
     * using the details text as a tiebreaker when several tables match.
     */
    public function up(): void
    {
        $sources = [
            'wish_comment' => ['table' => 'wish_comments', 'phrase' => 'wish preview'],
            'donation_comment' => ['table' => 'donation_comments', 'phrase' => 'donation preview'],
            'forum_comment' => ['table' => 'forum_comments', 'phrase' => 'forum preview'],
            'happy_story_comment' => ['table' => 'happy_story_comments', 'phrase' => 'happy story details'],
        ];

        $reports = DB::table('reports')->where('reportable_type', 'comment')->get();

        foreach ($reports as $report) {
            $resolvedType = null;

            // The system-written default details name the report's source page,
            // so trust that phrase first — it stays correct even after the
            // comment itself has been deleted.
            $details = strtolower((string) $report->details);
            foreach ($sources as $type => $source) {
                if (str_contains($details, $source['phrase'])) {
                    $resolvedType = $type;
                    break;
                }
            }

            if ($resolvedType === null) {
                $matches = [];
                foreach ($sources as $type => $source) {
                    $exists = DB::table($source['table'])
                        ->where('id', $report->reportable_id)
                        ->where('user_id', $report->reported_user_id)
                        ->exists();
                    if ($exists) {
                        $matches[] = $type;
                    }
                }

                if (count($matches) === 1) {
                    $resolvedType = $matches[0];
                }
            }

            if ($resolvedType !== null) {
                DB::table('reports')->where('id', $report->id)->update(['reportable_type' => $resolvedType]);
            }
        }
    }

    public function down(): void
    {
        DB::table('reports')
            ->whereIn('reportable_type', ['wish_comment', 'donation_comment', 'forum_comment', 'happy_story_comment'])
            ->update(['reportable_type' => 'comment']);
    }
};
