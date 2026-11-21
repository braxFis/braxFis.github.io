<style>
/* Match the existing COMA game pages: monochrome, strong type, fine rules. */
.coma-editorial{--ed-ink:#111;--ed-muted:#777;--ed-rule:#ddd;color:var(--ed-ink);width:min(1100px,92%);margin:60px auto 100px}
.coma-editorial *{box-sizing:border-box}
.coma-editorial a{color:inherit;text-decoration:none}
.coma-editorial a:focus-visible{outline:2px solid currentColor;outline-offset:5px}
.coma-editorial .ed-eyebrow{display:block;color:var(--ed-muted);font-size:9px;font-weight:800;letter-spacing:3px;text-transform:uppercase;margin:0 0 10px}
.coma-editorial .ed-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;border-bottom:2px solid var(--ed-ink);padding-bottom:20px;margin-bottom:36px}
.coma-editorial .ed-title{margin:0;font-size:clamp(36px,5vw,56px);font-weight:800;line-height:1.02;letter-spacing:-2px;overflow-wrap:anywhere}
.coma-editorial .ed-count{flex-shrink:0;font-size:9px;font-weight:700;letter-spacing:2px;color:var(--ed-muted);text-transform:uppercase}
.coma-editorial .ed-row{display:grid;grid-template-columns:38px minmax(0,1fr);gap:24px;padding:0 0 32px;margin-bottom:32px;border-bottom:1px solid var(--ed-rule)}
.coma-editorial .ed-row.has-image{grid-template-columns:38px 240px minmax(0,1fr)}
.coma-editorial .ed-number{font-size:11px;color:#999;font-weight:700;padding-top:3px}
.coma-editorial .ed-media{display:block;overflow:hidden;aspect-ratio:16/10;background:#f3f3f3}
.coma-editorial .ed-media img{display:block;width:100%;height:100%;object-fit:cover;transition:transform .3s}
.coma-editorial .ed-media:hover img{transform:scale(1.03)}
.coma-editorial .ed-copy{min-width:0}
.coma-editorial .ed-category{font-size:8px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:var(--ed-muted);margin:0 0 10px}
.coma-editorial .ed-row-title{font-size:clamp(24px,3vw,32px);font-weight:800;line-height:1.12;letter-spacing:-1px;margin:0 0 12px;overflow-wrap:anywhere}
.coma-editorial .ed-row-title a:hover{text-decoration:underline;text-underline-offset:4px;text-decoration-thickness:1px}
.coma-editorial .ed-excerpt{font-size:14px;line-height:1.7;color:#555;margin:0 0 18px;max-width:70ch}
.coma-editorial .ed-meta{display:flex;flex-wrap:wrap;gap:8px 20px;font-size:10px;line-height:1.6;color:var(--ed-muted)}
.coma-editorial .ed-link{display:inline-block;font-size:9px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;padding:4px 0}
.coma-editorial .ed-link:hover{text-decoration:underline;text-underline-offset:5px}
.coma-editorial .ed-row-bottom{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.coma-editorial .ed-pagination{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-top:40px;padding-top:20px;border-top:2px solid var(--ed-ink)}
.coma-editorial .ed-page-number{font-size:10px;color:var(--ed-muted)}
.coma-editorial .ed-empty{padding:32px 0 64px;color:#666;font-size:15px;line-height:1.7}
.coma-editorial .ed-back{margin-bottom:32px}
.coma-editorial .ed-hero{padding-bottom:25px;border-bottom:1px solid var(--ed-rule);margin-bottom:40px}
.coma-editorial .ed-hero .ed-title{max-width:960px;font-size:clamp(40px,6vw,72px);letter-spacing:-2.5px;margin-bottom:24px}
.coma-editorial .ed-cover{margin:0 0 45px;overflow:hidden;aspect-ratio:21/9;background:#f3f3f3}
.coma-editorial .ed-cover img{display:block;width:100%;height:100%;object-fit:cover}
.coma-editorial .ed-reading-layout{display:grid;grid-template-columns:minmax(0,740px) minmax(180px,1fr);gap:70px;align-items:start}
.coma-editorial .ed-body{font-size:16px;line-height:1.85;color:#333;min-width:0;overflow-wrap:anywhere}
.coma-editorial .ed-body>:first-child{margin-top:0}
.coma-editorial .ed-body p{margin:0 0 24px}
.coma-editorial .ed-body h2{font-size:28px;line-height:1.2;font-weight:800;letter-spacing:-.8px;color:var(--ed-ink);margin:44px 0 18px}
.coma-editorial .ed-body h3,.coma-editorial .ed-body h4{font-size:22px;line-height:1.3;color:var(--ed-ink);margin:32px 0 15px}
.coma-editorial .ed-body a{text-decoration:underline;text-underline-offset:3px}
.coma-editorial .ed-body img{display:block;max-width:100%;height:auto;margin:28px auto}
.coma-editorial .ed-body figure{max-width:100%;margin:30px 0}
.coma-editorial .ed-body figcaption{font-size:12px;line-height:1.6;color:var(--ed-muted);margin-top:10px}
.coma-editorial .ed-body blockquote{border-left:2px solid var(--ed-ink);padding-left:24px;margin:32px 0;font-size:20px}
.coma-editorial .ed-body ul,.coma-editorial .ed-body ol{padding-left:24px;margin:0 0 24px}
.coma-editorial .ed-body li{margin-bottom:8px}
.coma-editorial .ed-body table{display:block;width:100%;overflow:auto;border-collapse:collapse;margin:24px 0}
.coma-editorial .ed-body td,.coma-editorial .ed-body th{padding:10px 14px;border:1px solid var(--ed-rule);text-align:left}
.coma-editorial .ed-body pre{max-width:100%;overflow:auto;padding:18px;background:#f5f5f5;font-size:13px}
.coma-editorial .ed-aside{border-top:2px solid var(--ed-ink);font-size:12px;line-height:1.7}
.coma-editorial .ed-fact{border-bottom:1px solid var(--ed-rule);padding:18px 0}
.coma-editorial .ed-fact-label{display:block;font-size:8px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#888;margin-bottom:6px}
.coma-editorial .ed-fact p{margin:0}
.coma-editorial.ed-related{width:100%;margin:60px 0 0}
.coma-editorial.ed-related .ed-heading{margin-bottom:28px}
.coma-editorial.ed-related .ed-title{font-size:28px;letter-spacing:-1px}
.coma-editorial .ed-related-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:32px}
.coma-editorial .ed-related-card{min-width:0;border-bottom:1px solid var(--ed-rule);padding-bottom:24px}
.coma-editorial .ed-related-card .ed-media{margin-bottom:20px;aspect-ratio:16/9}
.coma-editorial .ed-related-card .ed-row-title{font-size:24px}
.coma-editorial .ed-related-card .ed-excerpt{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
@media(max-width:800px){
.coma-editorial .ed-row.has-image{grid-template-columns:180px minmax(0,1fr);gap:20px}
.coma-editorial .ed-row.has-image .ed-number{display:none}
.coma-editorial .ed-reading-layout{grid-template-columns:1fr;gap:40px}
.coma-editorial .ed-aside{max-width:none}
}
@media(max-width:600px){
.coma-editorial{margin:35px auto 60px}
.coma-editorial .ed-heading{align-items:flex-start;gap:18px;margin-bottom:28px}
.coma-editorial .ed-count{padding-top:5px;font-size:8px;letter-spacing:1px}
.coma-editorial .ed-row,.coma-editorial .ed-row.has-image{grid-template-columns:minmax(0,1fr);gap:16px}
.coma-editorial .ed-number{display:none}
.coma-editorial .ed-row-title{font-size:26px}
.coma-editorial .ed-hero .ed-title{letter-spacing:-1.5px}
.coma-editorial .ed-cover{aspect-ratio:16/10;margin-bottom:30px}
.coma-editorial .ed-body{font-size:15px;line-height:1.8}
.coma-editorial .ed-related-grid{grid-template-columns:1fr;gap:26px}
.coma-editorial.ed-related .ed-heading{flex-wrap:wrap}
}
@media(prefers-reduced-motion:reduce){.coma-editorial .ed-media img{transition:none}.coma-editorial .ed-media:hover img{transform:none}}
</style>
