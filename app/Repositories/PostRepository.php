<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function getPublishedPaginated(?string $search, int $pegPage): LengthAwarePaginator
    {
        $query = Post::query()->where('is_published', true);

        if ($search = trim((string) $search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('published_at')->orderByDesc('created_at')->paginate($pegPage)->withQueryString();
    }

    public function findPublishedBySlugOrFail(string $slug): Post
    {
        return Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return Post::latest()->paginate($perPage);
    }
}
