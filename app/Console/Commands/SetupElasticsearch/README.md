# Elastic Setup Requirements
List of used plugins, files with stopwords and synonyms used in the setup files.
You can find the list of resources also in the repository [elasticsearch-slovencina](https://github.com/SlovakNationalGallery/elasticsearch-slovencina)

## SK
- [LemmaGen Analysis for ElasticSearch](https://github.com/vhyza/elasticsearch-analysis-lemmagen)
- [stop-words-slovak.txt](https://github.com/SlovakNationalGallery/elasticsearch-slovencina/blob/master/stop-words/stop-words-slovak.txt)
- [sk_SK.txt](https://github.com/SlovakNationalGallery/elasticsearch-slovencina/blob/master/synonyms/sk_SK.txt) for synonyms

## CS
- [LemmaGen Analysis for ElasticSearch](https://github.com/vhyza/elasticsearch-analysis-lemmagen)
- [synonyms_cz](https://sites.google.com/site/kevinbouge/synonyms-lists/synonyms_cz.txt) for synonyms
- `stop-words-czech2.txt` from [stop-words](https://sites.google.com/site/kevinbouge/synonyms-lists/synonyms_cz.txt) for synonyms

## EN
- `wn_s.pl` from [Wordnet prolog database](http://wordnetcode.princeton.edu/3.0/WNprolog-3.0.tar.gz) for synonyms

## Testing analyzers

```
POST /webumenia_cs/_analyze
{
  "analyzer": "cestina",
  "text":  "nějaký text i s diakritikou pro testování"
}
```