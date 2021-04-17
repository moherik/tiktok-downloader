<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VideoController extends Controller
{
    public $tiktok;

    public function __construct()
    {
        $this->tiktok = new \Sovit\TikTok\Api();
    }

    public function index()
    {
        $video = \App\Models\Video::orderBy('created_at', 'DESC')->get();
        return response()->json($video);
    }


    public function show($id)
    {
        try {
            $video = \App\Models\Video::where('id', $id)->firstOrFail();
            return response()->json($video);
        } catch (HttpException $ex) {
            return response()->json(['error' => true, 'message' => $ex->getMessage()], $ex->getStatusCode());
        }
    }

    public function getVideo($url)
    {
        return $this->tiktok->getVideoByUrl($url);
    }

    public function streamVideo()
    {
        $url = request()->get("url");
        $video = $this->getVideo($url);
        $streamer = new \Sovit\TikTok\Stream();
        return $streamer->stream($video->items[0]->video->playAddr);
    }

    public function store(Request $request)
    {
        try {
            $video = $this->getVideo($request->url);
            $link = $this->getLink($request->url);
            $item = $video->items[0];
            $store = \App\Models\Video::firstOrCreate(
                ['tiktok_url' => $video->info->detail],
                [
                    'tiktok_url' => $video->info->detail,
                    'desc' => $item->desc,
                    'author_id' => $item->author->id,
                    'author_nickname' => $item->author->nickname,
                    'author_username' => $item->author->uniqueId,
                    'author_avatar' => $item->author->avatarThumb,
                    'signature' => $item->author->signature,
                    'vid_cover_url' => $item->video->cover,
                    'vid_play_url' => $item->video->playAddr,
                    'vid_download_url' => $link->nwm ?? "",
                    'vid_created_at' => $item->createTime,
                    'mus_title' => $item->music->title,
                    'mus_author' => $item->music->authorName,
                    'mus_play_url' => $item->music->playUrl,
                    'mus_cover_url' => $item->music->coverThumb,
                    'digg_count' => $item->stats->diggCount,
                    'share_count' => $item->stats->shareCount,
                    'comment_count' => $item->stats->commentCount,
                    'play_count' => $item->stats->playCount,
                ]
            );
            return response()->json(['message' => 'Sukses menyimpan data', 'id' => $store->id]);
        } catch (HttpException $ex) {
            return response()->json([ 'error' => true, 'message' => $ex->getMessage()], $ex->getStatusCode());
        }
    }

    public function getLink($url)
    {
        $client = new Client();
        $res = $client->request("GET", "https://glacial-shore-16133.herokuapp.com/get?t=".$url);
        return json_decode($res->getBody());
    }
}
