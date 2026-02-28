import os
import re

mapping = {
    'varicocele-hero.jpg': 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?auto=format&fit=crop&q=80&w=1200',
    'oligospermia-hero.jpg': 'https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=1200',
    'art_procedures_lab.png': 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&q=80&w=1200',
    'dna-frag-hero.jpg': 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80&w=1200',
    'azoospermia-hero.jpg': 'https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&q=80&w=1200',
    'pcos-hero.jpg': 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&q=80&w=1200',
    'endometriosis-hero.jpg': 'https://images.unsplash.com/photo-1579684453423-f84349ef60b0?auto=format&fit=crop&q=80&w=1200',
    'dor-hero.jpg': 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?auto=format&fit=crop&q=80&w=1200',
    'blocked-tubes-hero.jpg': 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&q=80&w=1200',
    'pgt-hero.jpg': 'https://images.unsplash.com/photo-1614935151651-0bea6508ab6b?auto=format&fit=crop&q=80&w=1200',
    'ivf-hero.jpg': 'https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=1200',
    'iui-hero.jpg': 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=1200',
    'icsi-hero.jpg': 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&q=80&w=1200'
}

files = [
    'male-infertility/varicocele.php',
    'male-infertility/low-sperm-count.php',
    'male-infertility/index.php',
    'male-infertility/dna-fragmentation.php',
    'male-infertility/azoospermia.php',
    'female-infertility/pcos.php',
    'female-infertility/endometriosis.php',
    'female-infertility/diminished-ovarian-reserve.php',
    'female-infertility/blocked-tubes.php',
    'art-procedures/index.php',
    'art-procedures/pgt.php',
    'art-procedures/ivf.php',
    'art-procedures/iui.php',
    'art-procedures/icsi.php'
]

for filepath in files:
    if not os.path.exists(filepath):
        continue
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Find <img src="/assets/images/X" ... onerror="...">
    # and the trailing <div>...</div> fallback block
    
    # 1. Replace src
    for old_file, new_url in mapping.items():
        content = content.replace(f'src="/assets/images/{old_file}"', f'src="{new_url}"')

    # 2. Remove onerror
    content = re.sub(r'\s*onerror=\"[^\"]+\"', '', content)

    # 3. Remove the fallback div block that usually follows immediately
    content = re.sub(r'<!-- Fallback if image not found -->.*?</div>', '', content, flags=re.DOTALL)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print('Processed', filepath)
